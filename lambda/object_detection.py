import boto3
from boto3.dynamodb.conditions import Key, Attr
import cv2
import os
import numpy as np
import json
import io 
import base64
import re

BUCKET_NAME = 'image-store-5225'
TABLE_NAME = 'images'

# Load all the objects that can be detected
LABELS = ['person', 'bicycle', 'car', 'motorbike', 'aeroplane', 'bus', 'train', 'truck', 'boat', 'traffic', 'light',
          'fire hydrant', 'stop sign', 'parking meter', 'bench', 'bird', 'cat', 'dog', 'horse', 'sheep', 'cow',
          'elephant', 'bear', 'zebra', 'giraffe', 'backpack', 'umbrella', 'handbag', 'tie', 'suitcase', 'frisbee',
          'skis', 'snowboard', 'sports ball', 'kite', 'baseball bat', 'baseball glove', 'skateboard', 'surfboard',
          'tennis racket', 'bottle', 'wine glass', 'cup', 'fork', 'knife', 'spoon', 'bowl', 'banana', 'apple',
          'sandwich', 'orange', 'carrot', 'pizza', 'cake', 'chair', 'sofa', 'bed', 'diningtable', 'toilet',
          'tvmonitor', 'laptop', 'mouse', 'remote', 'keyboard', 'cell phone', 'microwave', 'oven', 'toaster', 'sink', 'refrigerator', 'book', 'clock', 'vase', 'scissors', 'teddy bear', 'hair drier', 'toothbrush']

client = boto3.client('dynamodb')

confthres = 0.3
nmsthres = 0.1

# Function to put the image url and its objects
def put_dynamo(obj_class, img_url):
    data = client.put_item(
        TableName=TABLE_NAME,
        Item={
            "id": {
                "S": obj_class
            },
            "urls": {
                "L": [
                    {
                        "S": img_url
                    }
                ]
            }
        },
    )

    return obj_class + ' created'

# Function to update the image url and its objects
def update_dynamo(obj_class, img_url):

    new_urls = {
        "L": [
            {
                "S": img_url
            }
        ]
    }

    data = client.update_item(
        TableName=TABLE_NAME,
        ConditionExpression="attribute_not_exists({})".format(obj_class),
        Key={
            'id': {
                'S': obj_class
            }
        },
        UpdateExpression="SET #l = list_append(#l, :vals)",
        ExpressionAttributeNames={
            "#l":  'urls'
        },
        ExpressionAttributeValues={
            ":vals":  new_urls
        }
    )

    return obj_class + ' updated'


def do_prediction(image, net, LABELS):
    """Perform object detection.

    :param image: Image to process in base64
    :param net: Object Detection model
    :param LABELS: Possible class labels
    """

    (H, W) = image.shape[:2]
    # determine only the *output* layer names that we need from YOLO
    ln = net.getLayerNames()
    ln = [ln[i[0] - 1] for i in net.getUnconnectedOutLayers()]

    blob = cv2.dnn.blobFromImage(image, 1 / 255.0, (416, 416),
                                 swapRB=True, crop=False)
    net.setInput(blob)
    layerOutputs = net.forward(ln)

    boxes = []
    confidences = []
    classIDs = []

    # loop over each of the layer outputs
    for output in layerOutputs:
        # loop over each of the detections
        for detection in output:
            # extract the class ID and confidence (i.e., probability) of
            # the current object detection
            scores = detection[5:]
            # print(scores)
            classID = np.argmax(scores)
            # print(classID)
            confidence = scores[classID]

            # filter out weak predictions by ensuring the detected
            # probability is greater than the minimum probability
            if confidence > confthres:
                # scale the bounding box coordinates back relative to the
                # size of the image, keeping in mind that YOLO actually
                # returns the center (x, y)-coordinates of the bounding
                # box followed by the boxes' width and height
                box = detection[0:4] * np.array([W, H, W, H])
                (centerX, centerY, width, height) = box.astype("int")

                # use the center (x, y)-coordinates to derive the top and
                # and left corner of the bounding box
                x = int(centerX - (width / 2))
                y = int(centerY - (height / 2))

                # update our list of bounding box coordinates, confidences,
                # and class IDs
                boxes.append([x, y, int(width), int(height)])

                confidences.append(float(confidence))
                classIDs.append(classID)

    # apply non-maxima suppression to suppress weak, overlapping bounding boxes
    idxs = cv2.dnn.NMSBoxes(boxes, confidences, confthres,
                            nmsthres)

    objects = []
    if len(idxs) > 0:
        # loop over the indexes we are keeping
        for i in idxs.flatten():
            if confidences[i] > 0.5:
                # Create an object describing the object -> label, confidence and bounding box
                objects.append(LABELS[i])

    return objects

def decode_base64(data, altchars=b'+/'):
    """Decode base64, padding being optional.

    :param data: Base64 data as an ASCII byte string
    :returns: The decoded byte string.

    """
    data = re.sub(rb'[^a-zA-Z0-9%s]+' % altchars, b'', data)  # normalize
    missing_padding = len(data) % 4
    if missing_padding:
        data += b'='* (4 - missing_padding)
    return base64.b64decode(data, altchars)

def lambda_handler(event, context):
    if event:

        s3 = boto3.client("s3")

        filename = json.loads(event['body'])['filename']
        print("FILENAME", filename)

        # Get url from of the images from the s3 bucket testuploadimagedte'

        s3_url = s3.generate_presigned_url('get_object',
                                          Params={'Bucket': BUCKET_NAME, 'Key': filename})  


        fileObj = s3.get_object(Bucket=BUCKET_NAME, Key=filename)  
        file_content = fileObj["Body"].read()
        decoded = decode_base64(file_content)
        np_array = np.fromstring(decoded, np.uint8)
        image_np = cv2.imdecode(np_array, cv2.IMREAD_COLOR)  
        # Read the required files to run yolo algorithem
        net_obj = cv2.dnn.readNet(
            '/opt/yolo_tiny_configs/yolov3-tiny.weights', '/opt/yolo_tiny_configs/yolov3-tiny.cfg')

        objects = do_prediction(image_np, net_obj, LABELS)

        added_dynamo_refs = []
        for obj in objects:
            try:
                added_dynamo_refs.append(update_dynamo(obj, s3_url))
            except Exception as e:
                added_dynamo_refs.append(put_dynamo(obj, s3_url))

        response = {
            'statusCode': 200,
            'body': "Succesfully added/updated stuff: {}".format(" ".join(added_dynamo_refs)),
            'headers': {
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin': '*'
            },
        }

        return response