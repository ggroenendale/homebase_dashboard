#!/usr/bin/env python
# -*- coding: utf-8 -*-

# The purpose of this file is to grab MileIQ .csv
# files from email and then parse the values before
# saving into a mysql database to then be stored and
# retrieved for a central dashboard view.

#Import libraries
import email
import getpass
import imaplib
import os
import sys
import mysql.connector
from html.parser import HTMLParser


# Get environment variables
# These values are set inside of the /etc/environment
# file in the format of:
# 	NAME_OF_VARIABLE_IN_CAPS=valuewithoutquotes
#
host = os.environ["HOMEBASE_DB_HOST"]
user = os.environ["HOMEBASE_DB_USER"]
passwd = os.environ["HOMEBASE_DB_PASS"]
db = os.environ["HOMEBASE_DB_BASE"]
emailuser = os.environ["HOMEBASE_EMAIL_USER"]
emailpass = os.environ["HOMEBASE_EMAIL_PASS"]

db = mysql.connector.connect(host=host, user=user,passwd=passwd, db=db)

class GHTMLParser(HTMLParser):
	def handle_starttag(self, tag, attrs):
		if tag == 'a':
			print ('Found a link!', tag, 'With attributes: ')
			for key, value in attrs:
				if key == 'saferedirect':
					print ("	attr: ", key)
					print ("	value: ", value)
				elif key == 'href':
					print ("	attr: ", key)
					print ("	value: ", value)

	def handle_endtag(tag,attrs):
		if tag == 'a':
			print ('Found an end to the link!')


def check_db(dbcon, tablename):
	curs = dbcon.cursor()
	query = ("""SELECT COUNT(*) FROM information_schema.tables WHERE table_name = '{0}'""".format(tablename.replace('\'','\'\'')))
	try:
		curs.execute(query)
		#print('Table exists')
		#db.commit()
		if curs.fetchone()[0] == 1:
			#curs.close()
			return True
		else:
			return False
	except:
		#print('Table does not exist or query error')
		#curs.close()
		db.rollback()
		return False

# userName = input('Enter your GMail username:')
# passwd = getpass.getpass('Enter your password: ')

user 		= emailuser
password 	= emailpass

parser = GHTMLParser()
imapSession = imaplib.IMAP4_SSL('imap.gmail.com')
typ, accountDetails = imapSession.login(user, password)
if typ != 'OK':
	print ('Not able to sign in!')
	raise

imapSession.select('Inbox')
typ, data = imapSession.search(None, '(FROM "mileiq")')
if typ != 'OK':
	print ('Error searching Inbox')
	raise

for msgId in data[0].split():
	typ, messageParts = imapSession.fetch(msgId, '(RFC822)')
	if typ != 'OK':
		print ('Error fetching mail.')
		raise
	emailbody = messageParts[0][1]
	#print (emailbody)
	mail = email.message_from_bytes(emailbody)
	for part in mail.walk():
		if part.get_content_maintype() == 'multipart':
			print ("!*!*!*!*!*!*!*!*!*!Here's a part!*!*!*!*!*!*!*!*!*!")
			parser.feed(part.as_string())
			# print (part.as_string())
			continue
		if part.get('Content-Disposition') is None:
			parser.feed(part.as_string())

			# print (part.as_string())
			continue
		# fileName = part.get_filename()
		# print (fileName)

		# if bool(fileName):
		# 	filepath = os.path.join(detach_dir, 'attachments', fileName)
		# 	if not os.path.isfile(filePath):
		# 		print fileName
		# 		fp = open(filePath, 'wb')
		# 		fp.write(part.get_payload(decode=True))
		# 		fp.close()
imapSession.close()
imapSession.logout()

print ('Not able to download all attachments.')
