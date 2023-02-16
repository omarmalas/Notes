#!/usr/bin/env python3
import subprocess
import argparse
import re

def get_arguments():

    parser = argparse.ArgumentParser(prog="MAC_CHANGER", description="Tool to change your MAC address")

    parser.add_argument("-i", "--interface", dest="interface", help="Interface to change it's MAC address")

    parser.add_argument("-m", "--mac", dest="New_Mac", help="New MAC address here")

    return parser.parse_args()


def change_Mac(interface, New_Mac):
    print("\n [+] Changing MAC address to ", New_Mac)

    subprocess.run(["sudo", "ifconfig", interface, "down"])

    subprocess.run(["sudo", "ifconfig", interface, "hw", "ether", New_Mac])

    subprocess.run(["sudo", "ifconfig", interface, "up"])


def check_mac_process(interface):

    ifconfig_result = subprocess.check_output(["ifconfig",interface]).decode('utf-8')
    current_mac_filtired = re.search(r"\w\w:\w\w:\w\w:\w\w:\w\w:\w\w", ifconfig_result)
    if current_mac_filtired:
        return current_mac_filtired.group(0)
    else:
        print("\n [-] no mac address for ",interface)


def error_handling(current_mac,New_Mac):

    if current_mac == New_Mac:

        print("\n [+] Successfully changed your MAC address to ", New_Mac)

    else:

        print("\n [-] did not change MAC address :(")


values = get_arguments()

current_mac = check_mac_process(values.interface)

print("\n [+] Current Mac = ",str(current_mac))

change_Mac(values.interface, values.New_Mac)

current_mac = check_mac_process(values.interface)

error_handling(current_mac,values.New_Mac)
