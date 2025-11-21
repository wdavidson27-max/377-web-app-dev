file = open("day5.txt", "r")
lines = file.readlines()

stacks = [
    ['G', 'D', 'V', 'Z', 'J', 'S', 'B'],
    ['Z', 'S', 'M', 'G', 'V', 'P'],
    ['C', 'L', 'B', 'S', 'W', 'T', 'Q', 'F'],
    ['H', 'J', 'G', 'W', 'M', 'R', 'V', 'Q'],
    ['C', 'L', 'S', 'N', 'F', 'M', 'D'],
    ['R', 'G', 'C', 'D'],
    ['H', 'G', 'T', 'R', 'J', 'D', 'S', 'Q'],
    ['P', 'F', 'V'],
    ['D', 'R', 'S', 'T', 'J'],
]


for line in lines:
    line = line.strip()

    words = line.split(' ')

    num_blocks = int(words[1])
    source = int(words[3]) - 1
    destination = int(words[5]) - 1
    pt2List = []
    
    
    for i in range(num_blocks):
        block = stacks[source].pop()
        pt2List.append(block)
    
    pt2List.reverse()
    for j in range(len(pt2List)):
        stacks[destination].append(pt2List[j])
        
for i in range(len(stacks)):
    print(stacks[i][-1], end='')

print()