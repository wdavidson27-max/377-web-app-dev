file = open("day3.txt", "r")
lines = file.readlines()

total = 0

for line in lines:
    line = line.strip()

    numbers = [int(x) for(x) in line]
    
    max = 0
    max2 = 0
    pos1 = 0

    for i in range(len(numbers) - 1):
        if max < numbers[i]:
            max = numbers[i]
            pos1 = i
            

    for j in range(pos1 + 1,(len(numbers))):
         if max2 < numbers[j]:
             max2 = numbers[j]
             pos2 = j
            
    
    total += max * 10 + max2
print(total)


# pt 2

file = open("day3.txt", "r")
lines = file.readlines()

def total_joltage(amount):
    total = 0

    for line in lines:
        line = line.strip()

        numbers = [int(x) for x in line]

        if amount > len(numbers):
            continue

        pos = -1
        num = ""

        for pick in range(amount):
            max_digit = 0
            max_pos = pos

            for i in range(pos + 1, len(numbers) - (amount - pick) + 1):
                if numbers[i] > max_digit:
                    max_digit = numbers[i]
                    max_pos = i

            num += str(max_digit)
            pos = max_pos

        total += int(num)

    return total
print(total_joltage(200))


    


             