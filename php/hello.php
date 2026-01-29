<html>
    <head>
        <title>PHP Lesson 1</title>
    </head> 

    <body>
        <h1>PHP Lesson 1</h1>

        <p>This is the first PHP lesson with simple PHP markup.


            <?php 
            
            $count = 0;
            for ($i = 0; $i < 10; $i++)
            {
                echo "Hello<br>"; 
                $count++;
            }    
                
            echo $count;
            
            ?>

        </p>
    </body>



</html