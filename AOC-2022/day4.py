file = open("day4.txt", "r")
lines = file.readlines()

count=0

for line in lines:
    line = line.strip()
    halves = line.split(",")
    range1 = halves[0].split("-")
    range2 = halves[1].split("-")

    s1=int(range1[0])
    e1=int(range1[1])
    s2=int(range2[0])
    e2=int(range2[1])

    if (s2 >= s1 and e2 <= e1) or (s1 >= s2 and e1 <= e2):
        count +=1

print(count)