#Simple python script to pull banking info via the Buxfer API
import sys
import os
import re
import datetime
import base64
import simplejson
import urllib.request
import mysql.connector

# Get environment variables
host = os.environ["HOMEBASE_DB_HOST"]
user = os.environ["HOMEBASE_DB_USER"]
passwd = os.environ["HOMEBASE_DB_PASS"]
base = os.environ["HOMEBASE_DB_BASE"]

# Connect to the database
db = mysql.connector.connect(host=host, user=user,passwd=passwd, db=base)

# Define Buxfer API access credentials
username = os.environ["HOMEBASE_BUXFER_EMAIL"]
password = os.environ["HOMEBASE_BUXFER_PASS"]

# Define API base_url variable
base_url = 'https://www.buxfer.com/api';

# Check error function to make sure that http requests go through without a hitch
def check_error(response):
	result = simplejson.load(response)
	response = result['response']
	if response['status'] != "OK":
		print("An error occurred: %s" % response['status'].replace('ERROR: ', ''))
		sys.exit(1)

	return response
# get_token() function will launch the login url with the userid and password from the
# environment variables, retrieve the response, get the token, and then return the token
# as a string
def get_token():
    # First create the url with the username and password
    login = base_url + '/login?userid=' + username + '&password=' + password;
    # Next use the urllib to open the url and get a request object
    req = urllib.request.urlopen(login)
    # Check the response object for errors and print the error if there is one
    response = check_error(req)
    # If everything is ok get the token
    token = response['token']
    # Return the token found in the response body
    return token

# get_tags() function will retrieve all tags used in Buxfer
def get_tags():
    # Use the token to get tags
    tags_url = base_url + '/tags?token=' + get_token();
    req = urllib.request.urlopen(tags_url)
    # Take the request body and turn it into json
    response = simplejson.load(req)
    res_body = response['response']
    tags = res_body['tags']
    # Print all of the tags to the terminal
    for tag in tags:
        print(tag['name'])

# get_accounts() function will retrieve all accounts that are in Buxfer
def get_accounts():
    # Use the token to get accounts
    accounts_url = base_url + '/accounts?token=' + get_token();
    req = urllib.request.urlopen(accounts_url)
    # Take the request body and turn it into json
    response = simplejson.load(req)
    res_body = response['response']
    accounts = res_body['accounts']
    # Print all of the account balances to the terminal
    for account in accounts:
        print(account['balance'])
    # Make the create table query for account balances
    query_create_accounts = "CREATE TABLE financial_accounts (account_id INT AUTO_INCREMENT PRIMARY KEY, buxfer_id INT(10), read_date VARCHAR(10), read_time VARCHAR(12), balance DOUBLE(8,2))"
    query_ins_accounts = ("INSERT INTO financial_accounts (buxfer_id, read_time, balance) VALUES (%s, %s, %s, %s)")

def main():
    # Use the token to get accounts
    accounts_url = base_url + '/tags?token=' + get_token();
    req = urllib.request.urlopen(accounts_url)
    # Take the request body and turn it into json
    response = simplejson.load(req)
    res_body = response['response']
    print(response)

    #client = db.cursor()

    #query =

    #client.execute(query)
    #db.commit

if __name__ == "__main__":
    main()
