import requests

#Not using unit tests

url = "https://raynorelgie.com/PictureThis/upload.php"

tests = [("true1.jpg", True, True, "TAGS:  highland,  mountain,  landscape"),
 ("true1.jpg", False, True, "TAGS:  highland,  mountain,  landscape"), #We want to be able to upload duplicates
 ("false1.empty", True, False, ""), #We cannot upload empty files
 ("true2.gif", False, True, "TAGS: None")] #A gif will be uploadable, but have no tags

def test(imageName, public, should_accept, tags):
    toupload = {'imgFile[]': open(imageName, 'rb')}
    otherdata = {'submit': 'yes', 'public': 'off'}
    if public:
        otherdata['public'] = 'on'
        
    ret = requests.post(url, files = toupload, data = otherdata).text
    
    accepted = "Your images are below." in ret
    return (accepted == should_accept, tags in ret)



for t in tests:
    accepted, tags = test(t[0], t[1], t[2], t[3])
    if accepted:
        print(t[0], ": SUCCESS (", str(t[2]), ")", sep="")
    else:
        print(t[0], "FAILURE")
    if tags:
        print("Tagging API: SUCCESS")
    else:
        print("Tagging API: DISCREPANCY")
    print("--")