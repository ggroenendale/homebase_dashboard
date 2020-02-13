<?php
//State the namespace this file is a part of.
namespace Homebase\API;

/**
 * Enable the tracking and management of daily calories consumed.
 * Global field variable for current calories consumed?
 * Functions based on weight and activity for how much calories are allowed.
 * Communication with a Document database for foods and their corresponding nutritional values.
 */

/**
 * Step by step process
 * 1.) User inputs weight, height, activity level, BMI
 * 2.) User inputs goal weight
 * 3.) Calculate desired calories for the day
 * 4.) User eats a banana
 * 5.) User inputs into system that a banana was eaten
 * 6.) Record consumption of food, reduction in calories used, and update calories left
 * 7.) Update other nutritional values like macros consumed and what not.
 */
Class Calories {

    public $test;
}