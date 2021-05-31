import json
import boto3
from boto3.dynamodb.conditions import Key, Attr
from urllib.parse import unquote


clientDynamodb = boto3.client('dynamodb')
dynamodbResource = boto3.resource('dynamodb')


def lambda_handler(event, context):
    req = event['body']
    req_data = dict(qc.split("=") for qc in req.split("&"))

    newTags = req_data['newTags'].split("-")
    objectUrl = unquote(req_data['imgUrl'])

    dynamodbTable = dynamodbResource.Table('images')

    for tag in newTags:
        data = clientDynamodb.get_item(
            TableName='images',
            Key={
                'id': {
                    'S': tag
                }
            }
        )
        try:
            item = data['Item']
            existingUrls = item['urls']['L']
            newUrls = []
            for url in existingUrls:
                newUrls.append(url['S'])
            if objectUrl not in newUrls:
                newUrls.append(objectUrl)
            dynamodbTable.update_item(
                Key={'id': tag}, UpdateExpression="SET urls = :updated", ExpressionAttributeValues={':updated': newUrls})
        except:
            clientDynamodb.put_item(
                TableName='images',
                Item={
                    "id": {
                        "S": tag
                    },
                    "urls": {
                        "L": [
                            {
                                "S": objectUrl
                            }
                        ]
                    }
                },
            )

    response = {
        'statusCode': 200,
        'body': data,
        'headers': {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
    }

    return response
