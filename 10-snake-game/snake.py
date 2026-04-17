'''
I added in a game over screen animation that comes down from the top of the screen
when the game ends and it shows the players score and gives them the option to
play again or quit
'''

"""
I also added something that increases the speed of the snake slowly as
you get more and more points
"""

import pygame
import time
import random
 
pygame.init()
 
white = (255, 255, 255)
yellow = (255, 255, 102)
black = (0, 0, 0)
red = (213, 50, 80)
green = (0, 255, 0)
blue = (50, 153, 213)
 
dis_width = 600
dis_height = 400
 
dis = pygame.display.set_mode((dis_width, dis_height))
pygame.display.set_caption('Snake Game by Edureka')
 
clock = pygame.time.Clock()
 
snake_block = 10
snake_speed = 15
 
font_style = pygame.font.SysFont("bahnschrift", 25)
score_font = pygame.font.SysFont("comicsansms", 35)
 
 
def Your_score(score):
    value = score_font.render("Your Score: " + str(score), True, yellow)
    dis.blit(value, [0, 0])
 
 
def our_snake(snake_block, snake_list):
    for x in snake_list:
        pygame.draw.rect(dis, black, [x[0], x[1], snake_block, snake_block])
 
 
def message(msg, color):
    mesg = font_style.render(msg, True, color)
    dis.blit(mesg, [dis_width / 6, dis_height / 3])


# Where the new game over screen is defined
def animated_game_over(score):
    overlay = pygame.Surface((dis_width, dis_height))
    overlay.fill(black)

    box = 0
    box_y = -200  

    while True:
        dis.fill(blue)

        # This makes it fade in 
        if box < 180:
            box += 5
        overlay.set_alpha(box)
        dis.blit(overlay, (0, 0))

        # This makes it slide down
        if box_y < dis_height / 3:
            box_y += 5

        box_width = 400
        box_height = 200
        box_x = (dis_width - box_width) / 2

        pygame.draw.rect(dis, white, [box_x, box_y, box_width, box_height])
        pygame.draw.rect(dis, black, [box_x, box_y, box_width, box_height], 3)

        # Text
        title = score_font.render("GAME OVER", True, red)
        dis.blit(title, (box_x + 90, box_y + 20))

        score_text = font_style.render(f"Score: {score}", True, black)
        dis.blit(score_text, (box_x + 140, box_y + 80))

        msg = font_style.render("C = Play Again | Q = Quit", True, black)
        dis.blit(msg, (box_x + 60, box_y + 130))

        pygame.display.update()

        
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                return "quit"
            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_q:
                    return "quit"
                if event.key == pygame.K_c:
                    return "restart"

        clock.tick(60)
 
 
def gameLoop():
    game_over = False
    game_close = False

    current_speed = snake_speed
 
    x1 = dis_width / 2
    y1 = dis_height / 2
 
    x1_change = 0
    y1_change = 0
 
    snake_List = []
    Length_of_snake = 1
 
    foodx = round(random.randrange(0, dis_width - snake_block) / 10.0) * 10.0
    foody = round(random.randrange(0, dis_height - snake_block) / 10.0) * 10.0
 
    while not game_over:

        # New game over loop that has the animation screen
        if game_close:
            action = animated_game_over(Length_of_snake - 1)

            if action == "quit":
                game_over = True
                game_close = False
            elif action == "restart":
                return
 
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                game_over = True
            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_LEFT:
                    x1_change = -snake_block
                    y1_change = 0
                elif event.key == pygame.K_RIGHT:
                    x1_change = snake_block
                    y1_change = 0
                elif event.key == pygame.K_UP:
                    y1_change = -snake_block
                    x1_change = 0
                elif event.key == pygame.K_DOWN:
                    y1_change = snake_block
                    x1_change = 0
 
        if x1 >= dis_width or x1 < 0 or y1 >= dis_height or y1 < 0:
            game_close = True

        x1 += x1_change
        y1 += y1_change

        dis.fill(blue)
        pygame.draw.rect(dis, green, [foodx, foody, snake_block, snake_block])

        snake_Head = []
        snake_Head.append(x1)
        snake_Head.append(y1)
        snake_List.append(snake_Head)

        if len(snake_List) > Length_of_snake:
            del snake_List[0]
 
        for x in snake_List[:-1]:
            if x == snake_Head:
                game_close = True
 
        our_snake(snake_block, snake_List)
        Your_score(Length_of_snake - 1)
 
        pygame.display.update()

        # This loop now has the speed increase in it
        if x1 == foodx and y1 == foody:
            foodx = round(random.randrange(0, dis_width - snake_block) / 10.0) * 10.0
            foody = round(random.randrange(0, dis_height - snake_block) / 10.0) * 10.0
            Length_of_snake += 1

            current_speed += 2
 
        clock.tick(current_speed)  
 
    pygame.quit()
    quit()
 
# I changed this to a while so that a loop is created outside of the function 
while True:
    gameLoop()