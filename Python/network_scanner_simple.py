#!/usr/bin/env python3

import scapy.all as scapy
import argparse
line="-"

def get_user_input():
    user_input = argparse.ArgumentParser(prog="NetScanner", description="Scan a network for live hosts")
    getting_user_input =user_input.add_argument("-t","--target", dest="ip_addr", help="Enter IP address to scan the network")

    return user_input.parse_args()

def scan_function(ip):

    arp_request = scapy.ARP(pdst=ip) # This sends ARP arp reques message to a destination by specifing the pdst && it takes a psrc (packet source) to carry out either simple arp spoofing or to carry out sophisticated attacks

    broadcast = scapy.Ether(dst="ff:ff:ff:ff:ff:ff") # the point of using Ether function is to make sure that the packet is sent to the BRROADCAST Mac address and because the destination mac address can be assigned here

    arp_request_broadcast = broadcast/arp_request

    answered_list, unanswered_list = scapy.srp(arp_request_broadcast, timeout=5,verbose = False)#scapy.srp allows us to send and receive packets with a custom ethernet part of the packet srp stands for send and recieve packet and it returns a (list aka [array])# timeout defines the waiting time for the program until it gets a response

    devices_list = []
    for x in answered_list:
        devices_dictionary = {"ip" : x[1].psrc, "MAC" : x[1].hwsrc}
        devices_list.append(devices_dictionary)#The -->list .append(obj) adds an obj to the end of an existing list

    # print(scapy.ls(scapy.Ether())) #scapy.ls() shows the options that can be passed to a scapy function

    # print(arp_request_broadcast.summary()) # some_var .summary() This explains what is happening in this case scapy is sending an ARP request to the broadcast address

    # arp_request_broadcast.show() # some_var .show() This shows what the defined variable does..
    return devices_list

def print_function(devices_list):
    print(" IP\t\t\t\t MAC_ADDRESS\n",line*60)
    for x in devices_list:
        print(x["ip"],"\t\t\t",x["MAC"])

values=get_user_input()
scan = scan_function(values.ip_addr)
print_function(scan)
