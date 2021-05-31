import json
import boto3

from boto3.dynamodb.conditions import Key, Attr

client = boto3.client('dynamodb')


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
        URLS += ([dic["S"] for dic in packed])

    response = {
        'statusCode': 200,
        'body': json.dumps({'urls': list(set(URLS))}),
        'headers': {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
    }

    return response
