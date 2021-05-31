import json
import boto3
import urllib
from boto3.dynamodb.conditions import Key, Attr
from botocore.config import Config


BUCKET_NAME = 'image-store-5225'
client = boto3.client('dynamodb')
s3 = boto3.client("s3", config=Config(signature_version='s3v4'))


def auth_origin():
    pass


def lambda_handler(event, context):

    req = event['body']
    req_data = dict(qc.split("=") for qc in req.split("&"))
    queries = req_data['query'].split('-')
    print(queries)

    URLS = []
    for query in queries:
        data = client.get_item(
            TableName='images',
            Key={
                'id': {
                    'S': query
                }
            }
        )

        packed = data['Item']['urls']['L']
        img_keys = [dic["S"] for dic in packed]

        for key in img_keys:
            s3_url = s3.generate_presigned_url('get_object',
                                               Params={'Bucket': BUCKET_NAME, 'Key': key})
            URLS.append(urllib.parse.quote(s3_url))

    response = {
        'statusCode': 200,
        'body': json.dumps({'urls': list(set(URLS))}),
        # 'body': json.dumps(data),
        'headers': {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
    }

    return response
