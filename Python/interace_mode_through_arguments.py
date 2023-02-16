#!/usr/bin/env python3

import subprocess  # This module is responsible for running commands in terminal
import argparse  # This Module is responsible for getting user input from an argument


def get_args():
    parser = argparse.ArgumentParser(prog="changeInterfaceMode", description="set desired interface mode")
    parser.add_argument("-i", "--interface", dest="interfaces", help="select interface to change it's mode")
    parser.add_argument("-m", "--mode", dest="mode", help="monitor OR managed modes")
    return parser.parse_args()  # This 'parse_args()' returns the user input to the function


def change_mode(interafceinput, modeinput):
    subprocess.run(["sudo", "ifconfig", interafceinput, "down"])
    subprocess.run(["sudo", "iwconfig", interafceinput, "mode", modeinput])
    subprocess.run(["sudo", "ifconfig", interafceinput, "up"])
    print("Successfully changed Mode to " + modeinput)


values = get_args()
change_mode(values.interfaces, values.mode)
