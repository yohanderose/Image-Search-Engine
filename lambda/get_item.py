import json
import boto3
from boto3.dynamodb.conditions import Key, Attr


client = boto3.client('dynamodb')

def lambda_handler(event, context):

  data = client.get_item(
    TableName='images',
    Key={
        'id': {
          'S': 'person'
        }
    }
  )

  packed = data['Item']['urls']['L']
  urls = [dic["S"] for dic in packed]
  
  response = {
      'statusCode': 200,
      'body': urls,
      'headers': {
        'Content-Type': 'application/json',
        'Access-Control-Allow-Origin': '*'
      },
  }

  return response