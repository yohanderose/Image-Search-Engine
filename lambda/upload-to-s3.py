import re
import json
import base64
import boto3
import typing
import time
from urllib.parse import unquote
import io
import uuid

s3 = boto3.client('s3')
BUCKET_NAME = 'image-store-5225'


def invokeLambdaFunction(*, functionName: str = None, payload: typing.Mapping[str, str] = None):
    if functionName == None:
        raise Exception('ERROR: functionName parameter cannot be NULL')
    payloadStr = json.dumps(payload)
    payloadBytesArr = bytes(payloadStr, encoding='utf8')
    client = boto3.client('lambda')
    response = client.invoke(
        FunctionName=functionName,
        InvocationType="RequestResponse",
        Payload=payloadBytesArr
    )
    return response


def get_file_extension(file_name, decoded_file):
    extension = imghdr.what(file_name, decoded_file)
    extension = "jpg" if extension == "jpeg" else extension
    return extension


def decode_base64_file(data):
    """
    Fuction to convert base 64 to readable IO bytes and auto-generate file name with extension
    :param data: base64 file input
    :return: tuple containing IO bytes file and filename
    """
    if 'data:' in data and ';base64,' in data:
        # Break out the header from the base64 content
        header, data = data.split(';base64,')

    # Try to decode the file. Return validation error if it fails.
    try:
        decoded_file = base64.b64decode(data)
    except TypeError:
        TypeError('invalid_image')

    return io.BytesIO(decoded_file)


def check_key(key, file_extension):
    temp = boto3.resource('s3')

    try:
        temp.Object(BUCKET_NAME, key).load()
        return str(uuid.uuid1()) + '.' + file_extension
    except Exception as e:
        return key


def lambda_handler(event, context):
    data_ = event['body']
    data = dict(qc.split("=") for qc in data_.split("&"))

    name = data['name']
    ext = name.split('.')[-1]
    name = check_key(name, ext)
    print(name)

    image = unquote(data['image'])
    image = decode_base64_file(image)

    s3.put_object(Bucket=BUCKET_NAME, Key=name, Body=image)

    response = {'statusCode': 200, 'body': json.dumps({'filename': name}), 'headers': {
        'Access-Control-Allow-Origin': '*'}}
    res = invokeLambdaFunction(functionName='trigger-test', payload=response)
    return response
