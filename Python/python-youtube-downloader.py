#!/usr/bin/env python3
from pytube import Playlist

print("\nYoutube PLaylist Downloader")

URL = input("\nEnter The playlist URL you want to download: ")

playlist = Playlist(URL)


Res = input("\nEnter Video Quality: ")

try:
     print("\nPlease wait ...")
     for video in playlist.videos:
         stream = video.streams.filter(file_extension="mp4").get_by_resolution(Res)
         print ("\nDownloading: ", video.title)
         stream.download()
     print("\nDownload Success!")
except:
     print("\nSomething Went Wrong!")
