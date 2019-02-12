#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import time
import datetime
import glob
import psutil
import mysql.connector
from time import strftime
from subprocess import call

# Get environment variables
# These values are set inside of the /etc/environment
# file in the format of:
# 	NAME_OF_VARIABLE_IN_CAPS=valuewithoutquotes
# 	
host = os.environ["HOMEBASE_DB_HOST"]
user = os.environ["HOMEBASE_DB_USER"]
passwd = os.environ["HOMEBASE_DB_PASS"]
db = os.environ["HOMEBASE_DB_BASE"]

db = mysql.connector.connect(host=host, user=user,passwd=passwd, db=db)
print ('Debugging')

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

def main():
	cur = dbcon.cursor()
	print ('debugging main')

	if hasattr(psutil, "disk_usage"):
		home_total = psutil.disk_usage('/home/')
		root_total = psutil.disk_usage('/')

		i = 1
	else:
		home_total = {}
		root_total = {}

	sizes = set(list(home_total.keys()) + list(root_total.keys()))

	for size in sizes:
		if size in home_total:
			for ht_entry in home_total[size]:
				if ht_entry.label == '':
					subs = 'homesize'

				ht_sql = ("INSERT INTO ht_reads (stor_date, stor_time, stor_total, stor_used, stor_free) VALUES (CURDATE(). CURTIME(), %s, %s, %s)")
				ht_values = (ht_entry.total, ht_entry.used, ht_entry.free)

				if check_db(db, 'stor_reads') == False:
					ht_query = "CREATE TABLE stor_reads (stor_id INT AUTO_INCREMENT PRIMARY KEY, stor_date VARCHAR(10), stor_time VARCHAR(10), stor_total DOUBLE(18,2), stor_used DOUBLE(18,2), stor_free DOUBLE(18,2))"
					try:
						cur.execute(ht_query)
						db.commit()
						try:
							cur.execute(ht_sql, ht_values)
							db.commit()
						except:
							db.rollback()
					except:
						db.rollback()
				else:
					try:
						cur.execute(ht_sql, ht_values)
						db.commit()
					except:
						db.rollback()

	
if __name__ == "__main__":
	main()