import random

file = open('diceware.txt', 'r')
lines = file.readlines()

words = []
for i in range(5):
    d1 = random.randint(1, 6)
    d2 = random.randint(1, 6)
    d3 = random.randint(1, 6)
    d4 = random.randint(1, 6)
    d5 = random.randint(1, 6)
    words.append(lines[d1 + d2 + d3 + d4 + d5 - 5].strip())

    for line in lines:
        line = line.strip()
        line = line.split(" ")