#!/usr/bin/env python3

import subprocess


def monitor(interface):
    print("[+]Changing Mode to Monitor")
    subprocess.run(["sudo", "ifconfig", interface, "down"])
    subprocess.run(["sudo", "iwconfig", interface, "mode", "monitor"])
    subprocess.run(["sudo", "ifconfig", interface, "up"])


def managed(interface):
    print("[+]Changing Mode to managed")
    subprocess.run(["sudo", "ifconfig", interface, "down"])
    subprocess.run(["sudo", "iwconfig", interface, "mode", "managed"])
    subprocess.run(["sudo", "ifconfig", interface, "up"])


def change():
    interface = input("enter the interface you to change it's mode here > ")
    getinput = input("Select a number:\n1)Monitor Mode\n2)Managed Mode\n>")
    return interface, getinput


values = change()

for x in values[1]:  # , getinput2:
    if x == "1":
        monitor(values[0])
    elif x == "2":
        managed(values[0])
    else:
        change() #Here it takes passes the ball to the change function and then it doesn't find a wrong input nor a true one then it stops
