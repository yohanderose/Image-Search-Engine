import json
import boto3

client = boto3.client('dynamodb')


def lambda_handler(event, context):
    
  data = client.put_item(
  TableName='images',
  Item={
  "id": {
    "S": "elephant"
  },
  "urls": {
    "L": [
      {
        "S": "https://i.imgur.com/URlN5ID.jpg"
      }
    ]
  }
}
  
  )

  response = {
      'statusCode': 200,
      'body': "Succesfully put item",
      'headers': {
        'Content-Type': 'application/json',
        'Access-Control-Allow-Origin': '*'
      },
  }
    
  return response