#!/usr/bin/env python
# -*- coding: utf-8 -*-

# Derived from a couple scripts
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
	cur = db.cursor()
	print ('Debugging main')
	if hasattr(psutil, "sensors_temperatures"):
		temps = psutil.sensors_temperatures()
		#print ('Debugging temps')
		core = 1
	else:
		temps = {}
	if hasattr(psutil, "sensors_fans"):
		fans = psutil.sensors_fans();
		# print ('Debugging fans')
		
		## This iterator is for the number of fans
		i = 1
	else:
		fans = {}
	# if hasattr(psutil, "sensors_battery"):
	# 	battery = psutil.sensors_battery()
	# 	# print ('Debugging battery')
	# else:
	# 	battery = None

	#if not any((temps, fans, battery)):
	if not any((temps, fans)):
		# print("can't read any temperature, fans, or battery info")
		return
	#names = set(list(temps.keys()) + list(fans.keys()) + list(battery.keys()))
	names = set(list(temps.keys()) + list(fans.keys()))
	# Insert temperature reads into temp_reads table
	for name in names:
		if name in temps:
			for t_entry in temps[name]:
				# Make an alternate label when there is none
				if t_entry.label == '':
					substitute = 'core' + str(core)
					core += 1
				t_sql = ("INSERT INTO temp_reads (read_date, read_time, read_label, read_curr, read_high, read_crit) VALUES (CURDATE(), CURTIME(), %s, %s, %s, %s)")
				t_values = (t_entry.label or substitute, t_entry.current, t_entry.high, t_entry.critical)
				if check_db(db, 'temp_reads') == False:
					t_query = "CREATE TABLE temp_reads (read_id INT AUTO_INCREMENT PRIMARY KEY, read_date VARCHAR(10), read_time VARCHAR(12), read_label VARCHAR(255), read_curr DOUBLE(7,4), read_high DOUBLE(7,4), read_crit DOUBLE(7,4))"
					try:
						cur.execute(t_query)
						db.commit()
						try:
							cur.execute(t_sql,t_values)
							db.commit()
							print ('Query successful!')
						except:
							db.rollback()
							print ('Problem with temp query')
					except:
						db.rollback()
						print("Problem with create temp table query")
				else:
					try:
						cur.execute(t_sql,t_values)
						db.commit()
					except:
						db.rollback()
						print ('Problem with temp query after check db')
						print ("Query: ", t_sql)
						print ("Values: ", t_values)
		if name in fans:
			for f_entry in fans[name]:
				# Make an alternate label when there is none
				if f_entry.label == '':
					alt = 'fan' + str(i)
					i += 1
				# Insert fan speed reads into fan_reads table
				f_sql = "INSERT INTO fan_reads (read_date, read_time, read_label, read_curr) VALUES (CURDATE(), CURTIME(), %s, %s)"
				f_values = (f_entry.label or alt, f_entry.current)
				if check_db(db, 'fan_reads') == False:
					f_query = "CREATE TABLE fan_reads (read_id INT AUTO_INCREMENT PRIMARY KEY, read_date VARCHAR(12), read_time VARCHAR(12), read_label VARCHAR(255), read_curr DOUBLE(9,4))"
					try:
						cur.execute(f_query)
						db.commit()
						try:
							cur.execute(f_sql, f_values)
							db.commit()
						except:
							db.rollback()
							print ('Problem with fan query')
							print ("Query: ", f_sql)
							print ("Values: ", f_values)
					except:
						db.rollback()
						print ('Problem with create fan table query')
				else:
					try:
						cur.execute(f_sql, f_values)
						db.commit()
					except:
						db.rollback()
						print ('Problem with fan query after check db')
						print ("Query: ", f_sql)
						print ("Values: ", f_values)

		# if name in battery:
		# 	for entry in battery[name]:
		# 		# Insert battery reads into batt_reads table
		# 		b_sql = ("""INSERT INTO batt_reads (datetime,temperature) VALUES (%s,%s)""", (datetimeWrite, temp))
		# 		if check_db(db, 'batt_reads') == False:
		# 			b_query = ("""CREATE TABLE batt_reads (read_id INT AUTO_INCREMENT PRIMARY KEY, read_date DATE(), read_label VARCHAR(255), read_curr DOUBLE(7,4), read_high DOUBLE(7,4), read_crit DOUBLE(7,4))""")
		# 			try:
		# 				cur.execute(b_query)
		# 				db.commit()
		# 				try:
		# 					cur.execute(b_sql)
		# 					db.commit()
		# 				except:
		# 					db.rollback()
		# 					print ('Problem with batt query')
		# 			except:
		# 				db.rollback()
		# 				print ('Problem with create batt table query')
		# 		else:
		# 			try:
		# 				cur.execute(b_sql)
		# 				db.commit()
		# 			except:
		# 				db.rollback()
		# 				print ('Problem with batt query')
	cur.close()
	db.close()

if __name__ == "__main__":
	main()