function testArray() {
    let grades = [79, 80, 100, 94, 83, 92];

    $('#grades').html('Your grades are: ' + grades);

    let total = 0;
    for (let i = 0; i < grades.length; i++) {
        total += grades[i];
    }

    let average = total / grades.length;

    $('#average').html('Your average is: ' + average);
    
}