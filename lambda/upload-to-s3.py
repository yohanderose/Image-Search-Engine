import re
import json
import base64
import boto3
import typing
import time
from urllib.parse import unquote


s3 = boto3.client('s3')

def invokeLambdaFunction(*, functionName:str=None, payload:typing.Mapping[str, str]=None):
    if  functionName == None:
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


def lambda_handler(event, context):
    data_ = event['body']
    data = dict(qc.split("=") for qc in data_.split("&"))
    # print(data['name'])
    name = data['name']
    image = unquote(data['image'])

    s3.put_object(Bucket='image-store-5225', Key=name, Body=image)

    response = {'statusCode': 200, 'body': json.dumps({'filename': name}), 'headers': {'Access-Control-Allow-Origin': '*'}}
    res = invokeLambdaFunction(functionName='trigger-test', payload=response)
    return response