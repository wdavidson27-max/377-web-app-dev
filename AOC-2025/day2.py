file = open('day2.txt', 'r')
line = file.readlines()[0].strip(",")

total_1 = 0
total_2 = 0
ranges = line.split(",")
for the_range in ranges:
    start, end = (int(x) for x in the_range.split('-'))
    for n in range(start, end + 1):
        number = str(n)
        
        length = len(number)

        if number[:len(number) // 2] == number[len(number) // 2:]:
            total_1 += n

        for l in range(1, length):
            if length % l == 0:
                chunks = [number[i:i+l] for i in range(0, len(number), l)]
                if len(set(chunks)) == 1:
                    total_2 += n
                    break


print('Part 1: ' + str(total_1))
print('Part 2: ' + str(total_2))
