import random
from sys import argv


SPECIALS = "!@#$%^&*()_-+={}[]:;><,./?\|"

if len(argv) == 3:
    length = int(argv[1])
    
    includeNumber = "0" in argv[2]
    includeLower = "a" in argv[2]
    includeUpper = "A" in argv[2]
    includeSpecial = "!" in argv[2]

elif len(argv) == 1:
    length = int(input("How many characters are required? "))
    includeNumber = input("Does your password require digits?(Y/N) ").upper()[0] == "Y"
    includeSpecial = input("Does your password require special characters?(Y/N) ").upper()[0] == "Y"
    includeUpper = input("Does your password require an uppercase letter?(Y/N) ").upper()[0] == "Y"
    includeLower = input("Does your password require a lowercase letter?(Y/N) ").upper()[0] == "Y"
else:
    print("Expected usage: password-generator.py LENGTH PATTERN")
    print("where pattern contains one or more of the following: Aa0!")
    exit()

password = []

if includeNumber:
    password.append(str(random.randint(0, 9)))
if includeUpper:
    password.append(chr(ord("A") + random.randint(0, 25)))
if includeLower:
    password.append(chr(ord("a") + random.randint(0, 25)))
if includeSpecial:
    password.append(SPECIALS[random.randint(0, len(SPECIALS) - 1)])


while len(password) < length:
    choice = random.randint(1, 4)


    if choice == 1 and includeNumber:
        password.append(str(random.randint(0, 9)))
    if choice == 2 and includeUpper:
        password.append(chr(ord("A") + random.randint(0, 25)))
    if choice == 3 and includeLower:
        password.append(chr(ord("a") + random.randint(0, 25)))
    if includeSpecial:
        password.append(SPECIALS[random.randint(0, len(SPECIALS) - 1)])



random.shuffle(password)


print("".join(password))
