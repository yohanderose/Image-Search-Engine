import json
import boto3
from boto3.dynamodb.conditions import Key, Attr
from urllib.parse import unquote


clientS3 = boto3.client('s3')
clientDynamodb = boto3.client('dynamodb')
dynamodbResource = boto3.resource('dynamodb')


def lambda_handler(event, context):

    req = event['body']
    req_data = dict(qc.split("=") for qc in req.split("&"))
    s3Key = req_data['imgName']
    imgUrl = unquote(req_data['imgUrl'])
    print(imgUrl)
    print(s3Key)

    clientS3.delete_object(Bucket='image-store-5225', Key=s3Key)
    data = clientDynamodb.scan(TableName='images')
    dynamodbTable = dynamodbResource.Table('images')

    allItems = data['Items']
    allUrls = []
    allKeys = []
    for item in allItems:
        itemId = item['id']['S']
        itemUrls = item['urls']['L']
        newUrls = []
        found = False

        for url in itemUrls:
            objectKey = url['S']
            if objectKey == imgUrl:
                found = True
            else:
                newUrls.append(url['S'])

        if found:
            dynamodbTable.update_item(Key={'id': itemId},
                                      UpdateExpression="SET urls = :updated",
                                      ExpressionAttributeValues={':updated': newUrls})

    response = {
        'statusCode': 200,
        'body': found,
        'headers': {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
    }

    return response
