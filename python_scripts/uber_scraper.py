# Simple python script to pull Uber driver information
import os
import time
import sys
import math
import re

import urllib
import requests
from pyvirtualdisplay import Display
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.options import Options
import bs4 as bs



#The primary function of the python script
def main():

    #Specify url of the website to be scraped
    site_url = 'https://partners.uber.com'

    #run the display
    display = Display(visible=0, size=(1024,768))
    display.start()

    #Set options for chrome
    chrome_options = webdriver.ChromeOptions()
    chrome_options.add_argument("--headless")
    driver = webdriver.Chrome(executable_path='/usr/bin/chromedriver', chrome_options=chrome_options)

    #Load the url into the library and then into the page variable
    driver.set_window_size(1024,768)
    driver.get(site_url)

    #Find the phone input field
    phone_field = driver.find_element_by_id('useridInput')
    placeholder = phone_field.get_attribute("placeholder")
    phone_field.send_keys('5094390773')
    phone_field.send_keys(Keys.ENTER)

    #Printing to the terminal
    print('Here is the result:')
    print(placeholder)

    #Wait a couple seconds
    print('Sleeping 5 Seconds')
    time.sleep(5)

    #Find the recaptcha field
    print('Looking for recaptcha')
    recaptcha = driver.find_element_by_class_name('recaptcha-checkbox')
    recaptcha_info = recaptcha.get_attribute('role')


    #Find the password input field
    passw_field = driver.find_element_by_id('password')
    passw_infos = passw_field.get_attribute("name")
    passw_field.send_keys('')


    print(recaptcha_info)
    print(passw_infos)
    print('Was there a result?')

if __name__ == "__main__":
    main()
