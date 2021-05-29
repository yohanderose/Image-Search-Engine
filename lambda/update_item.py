import json
import boto3

client = boto3.client('dynamodb')


def lambda_handler(event, context):
    
  new_urls =  {
    "L": [
      {
        "S": "https://i.imgur.com/sTvQB6U.jpg"
      }
    ]
  }

  data = client.update_item(
        TableName='images',
        Key={
            'id': {
              'S' : 'elephant'
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
  
  response = {
      'statusCode': 200,
      'body': "Succesfully updated item",
      'headers': {
        'Content-Type': 'application/json',
        'Access-Control-Allow-Origin': '*'
      },
  }
    
  return response