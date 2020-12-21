# [PictureThis](https://raynorelgie.com/PictureThis/)
Image repository with access control, upload, searching, and feature tagging (using an API)
___
## index.php
![index](https://i.imgur.com/5WNUvip.png)

The index doubles as about page and search... try entering "[skyline](https://raynorelgie.com/PictureThis/?search=skyline)" in the search bar. Search returns matching/similar titles or tags. Public images only.

## profile.php
![profile](https://i.imgur.com/ka7lzUK.png)

View the images that a user has uploaded. If you are authenticated as the user, you will be able to see both private and public images. Otherwise, only public.

## upload.php
![upload](https://i.imgur.com/jRfe1q8.png)

Upload your own images, decide if they are public or not. Author is ANON if you aren't logged in, you if you are. After uploading, images are automatically tagged using Imagga API.

---
## Other pages...  
indexCode.php - Code used by index.php  
register.php - allows users to register  
logout.php - logs you out and redirects you to the index  
