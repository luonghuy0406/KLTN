# import cac thu vien can thiet
import cv2
import math
import numpy as np
import time
from threading import Thread
import threading
import mysql.connector
from datetime import datetime
import datetime
import RPi.GPIO as GPIO

# detect car function
def get_object(net, image, conf_threshold=0.5, h=360, w=640):
    blob = cv2.dnn.blobFromImage(cv2.resize(image, (300, 300)), 0.007843, (300, 300), 127.5)
    net.setInput(blob)
    detections = net.forward()
    boxes = []

    for i in range(0, detections.shape[2]):
        confidence = detections[0, 0, i, 2]
        if confidence > conf_threshold:
            idx = int(detections[0, 0, i, 1])
            if idx == 6 or idx == 7 or idx == 14:
                box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
                (startX, startY, endX, endY) = box.astype("int")
                box = [startX, startY, endX - startX, endY - startY]
                boxes.append(box)

    return boxes


# check old or new function
def is_old(center_Xd, center_Yd, boxes):
    for box_tracker in boxes:
        (xt, yt, wt, ht) = [int(c) for c in box_tracker]
        center_Xt, center_Yt = int((xt + (xt + wt)) / 2.0), int((yt + (yt + ht)) / 2.0)
        distance = math.sqrt((center_Xt - center_Xd) ** 2 + (center_Yt - center_Yd) ** 2)

        if distance < max_distance:
            return True
    return False


def get_box_info(box):
    (x, y, w, h) = [int(v) for v in box]
    center_X = int((x + (x + w)) / 2.0)
    center_Y = int((y + (y + h)) / 2.0)
    return x, y, w, h, center_X, center_Y


def cars_count():
    # Define parameters

    prototype_url = 'MobileNetSSD_deploy.prototxt'
    model_url = 'MobileNetSSD_deploy.caffemodel'
    video_path = 'video.MOV'
    Current_Date = datetime.datetime.today().strftime ('%Y-%m-%d')
    Current_Time = datetime.datetime.today().strftime ('%H:00:00')
    Current_Car=0
    global max_distance
    global vid
    global frame_count
    global car_number
    max_distance = 50
    input_h = 360
    input_w = 640
    laser_line = input_h - 50
    net = cv2.dnn.readNetFromCaffe(prototype_url, model_url)
    vid = cv2.VideoCapture(video_path)
    frame_count = 0
    car_number = 0
    obj_cnt = 0
    curr_trackers = []
    t = 0
    global cars
    global minute,seconds,temp
    cars = 0
    minute=0
    seconds=0
    temp = 0
    mydb = mysql.connector.connect(host="localhost",user="admin",passwd="admin",database="KLTN")
    mycursor = mydb.cursor()
    while vid.isOpened():

        laser_line_color = (0, 0, 255)
        boxes = []

        # Read image from video
        _, frame = vid.read()
        if frame is None:
            break

        # Resize 
        frame = cv2.resize(frame, (input_w, input_h))

        # Browse for objects in tracker
        old_trackers = curr_trackers
        curr_trackers = []

        for car in old_trackers:

            # Update tracker
            tracker = car['tracker']
            (_, box) = tracker.update(frame)
            boxes.append(box)

            new_obj = dict()
            new_obj['tracker_id'] = car['tracker_id']
            new_obj['tracker'] = tracker

            # Caculate centroid's object
            x, y, w, h, center_X, center_Y = get_box_info(box)

            # Draw a rectangle around object
            cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)

            # Draw a circle in centroid's object
            cv2.circle(frame, (center_X, center_Y), 4, (0, 255, 0), -1)
            cv2.putText(frame, "y="+str(center_Y), (center_X, center_Y), cv2.FONT_HERSHEY_SIMPLEX, 0.5, laser_line_color, 2)

            # Compare coordinate centroid's object with coordinate's laser line. 
            if center_Y > laser_line :
                # If it pass, then count cars and not track
                laser_line_color = (0, 255, 255)
                car_number += 1
            else:
                # if not continue track
                curr_trackers.append(new_obj)

        # detect object 20 frames/ 1 time
        if frame_count % 20 == 0:
            # Detect object
            boxes_d = get_object(net, frame)

            for box in boxes_d:
                old_obj = False

                xd, yd, wd, hd, center_Xd, center_Yd = get_box_info(box)

                if center_Yd <= laser_line - max_distance:

                    # Duyet qua cac box, neu sai lech giua doi tuong detect voi doi tuong da track ko qua max_distance thi coi nhu 1 doi tuong
                    if not is_old(center_Xd, center_Yd, boxes):
                        #cv2.rectangle(frame, (xd, yd), ((xd + wd), (yd + hd)), (0, 255, 255), 2)
                        # Tao doi tuong tracker moi

                        tracker = cv2.TrackerMOSSE_create()

                        obj_cnt += 1
                        new_obj = dict()
                        tracker.init(frame, tuple(box))

                        new_obj['tracker_id'] = obj_cnt
                        new_obj['tracker'] = tracker

                        curr_trackers.append(new_obj)
        # tinh so luong xe moi 10s
        if frame_count % 300 == 0:
            cars = car_number - t
            t = car_number
        # tinh thoi gian hien thi
        seconds = frame_count /30
        if seconds>0 and seconds %60==0:
            minute = minute + 1
            temp = temp + 60
        # Tang frame
        frame_count += 1

        # Hien thi so xe

        cv2.putText(frame, "Total cars: " + str(car_number), (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255 , 255), 2)
        cv2.putText(frame, "Cars/10s: " + str(cars), (10, 60), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255 , 255), 2)
        cv2.putText(frame, "Time: %d minute %d seconds" %(minute,seconds-temp), (10, 90), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 0, 0), 2)

           
        cv2.line(frame, (0, laser_line), (input_w, laser_line), laser_line_color, 2)
        cv2.putText(frame, str(laser_line), (10, laser_line - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, laser_line_color, 2)

        # Frame
        cv2.imshow("GRADUATION THESIS - SMART TRAFFIC LIGHT SYSTEN", frame)
        key = cv2.waitKey(1) & 0xFF
        if key == 27:
            break
    Current_Car = car_number *12
    sql = "INSERT INTO data (Date,Time,Cars_count) VALUES (%s, %s,%s)"
    val = (str(Current_Date),Current_Time,Current_Car)
    mycursor.execute(sql, val)

    mydb.commit()
    vid.release()
def traffic_light():
    time.sleep(2)
    GPIO.setmode(GPIO.BCM)
    digitBitmap = { 0: 0b00111111, 1: 0b00000110, 2: 0b01011011, 3: 0b01001111, 4: 0b01100110, 5: 0b01101101, 6: 0b01111101, 7: 0b00000111, 8: 0b01111111, 9: 0b01101111 }
    masks = { 'a': 0b00000001, 'b': 0b00000010, 'c': 0b00000100, 'd': 0b00001000, 'e': 0b00010000, 'f': 0b00100000, 'g': 0b01000000 }
    pins = { 'a': 17, 'b': 22, 'c': 6, 'd': 13, 'e': 19, 'f': 27, 'g': 5}
    masks2 = { 'a2': 0b00000001, 'b2': 0b00000010, 'c2': 0b00000100, 'd2': 0b00001000, 'e2': 0b00010000, 'f2': 0b00100000, 'g2': 0b01000000 }
    pins2 = { 'a2': 18, 'b2': 23, 'c2': 24, 'd2': 25, 'e2': 12, 'f2': 16, 'g2': 20}
   
    def renderChar1(c):
        val = digitBitmap[c]
        GPIO.output(list(pins.values()), GPIO.LOW)
        for k,v in masks.items():
            if val&v == v:
                GPIO.output(pins[k], GPIO.HIGH)
    def renderChar2(d):
        val = digitBitmap[d]
        GPIO.output(list(pins2.values()), GPIO.LOW)
        for k,v in masks2.items():
            if val&v == v:
                GPIO.output(pins2[k], GPIO.HIGH)
    
    GPIO.setup(list(pins.values()), GPIO.OUT)
    GPIO.setup(21, GPIO.OUT)
    GPIO.setup(26, GPIO.OUT)
    GPIO.setup(4, GPIO.OUT)
    GPIO.output(list(pins.values()), GPIO.LOW)
    GPIO.setup(list(pins2.values()), GPIO.OUT)
    GPIO.output(list(pins2.values()), GPIO.LOW)
    
    while vid.isOpened():
            #moi 20 seconds xet dieu kien 1 lan
            #vi qua trinh xu ly code nen 20s con 10s so voi thuc te

        if(cars<7 ):
            print("cars: ", cars)
            print("cars it")
            print("%d minute %d seconds"%(minute,seconds-temp))
            print("----------------------------------")
            red=5
            yellow=2
            green=3
            b=0
            c=0
            GPIO.output(21, GPIO.HIGH) #digitalWrite(18, HIGH)
            GPIO.output(26, GPIO.LOW) #digitalWrite(18, LOW)
            GPIO.output(4, GPIO.LOW) #digitalWrite(18, LOW)
            for i in range(red):
                c=red%10
                b=(red-c)/10
                red=red-1
                renderChar1(b)
                renderChar2(c)
                time.sleep(1)
                
            GPIO.output(21, GPIO.LOW) #digitalWrite(18, HIGH)
            GPIO.output(26, GPIO.LOW) #digitalWrite(18, LOW)
            GPIO.output(4, GPIO.HIGH) #digitalWrite(18, LOW)
            for i in range(green):
                c=green%10
                b=(green-c)/10
                green=green-1
                renderChar1(b)
                renderChar2(c)
                time.sleep(1)
                    
            GPIO.output(21, GPIO.LOW) #digitalWrite(18, HIGH)
            GPIO.output(26, GPIO.HIGH) #digitalWrite(18, LOW)
            GPIO.output(4, GPIO.LOW) #digitalWrite(18, LOW)
            for i in range(yellow):
                c=yellow%10
                b=(yellow-c)/10
                yellow=yellow-1
                renderChar1(b)
                renderChar2(c)
                time.sleep(1)

        if(cars>=7):
                
            print("cars: ", cars)
            print("cars nhieu")
            print("%d minute %d seconds"%(minute,seconds-temp))
            print("----------------------------------")
            red=2
            yellow=2
            green=6
            b=0
            c=0
            GPIO.output(21, GPIO.HIGH) #digitalWrite(18, HIGH)
            GPIO.output(26, GPIO.LOW) #digitalWrite(18, LOW)
            GPIO.output(4, GPIO.LOW) #digitalWrite(18, LOW)
            for i in range(red):
                c=red%10
                b=(red-c)/10
                red=red-1
                renderChar1(b)
                renderChar2(c)
                time.sleep(1)
                
            GPIO.output(21, GPIO.LOW) #digitalWrite(18, HIGH)
            GPIO.output(26, GPIO.LOW) #digitalWrite(18, LOW)
            GPIO.output(4, GPIO.HIGH) #digitalWrite(18, LOW)
            for i in range(green):
                c=green%10
                b=(green-c)/10
                green=green-1
                renderChar1(b)
                renderChar2(c)
                time.sleep(1)
                    
            GPIO.output(21, GPIO.LOW) #digitalWrite(18, HIGH)
            GPIO.output(26, GPIO.HIGH) #digitalWrite(18, LOW)
            GPIO.output(4, GPIO.LOW) #digitalWrite(18, LOW)
            for i in range(yellow):
                c=yellow%10
                b=(yellow-c)/10
                yellow=yellow-1
                renderChar1(b)
                renderChar2(c)
                time.sleep(1)
        
            

t1 = threading.Thread(target=cars_count)
t2 = threading.Thread(target=traffic_light)
t1.start()
t2.start()

cv2.destroyAllWindows
