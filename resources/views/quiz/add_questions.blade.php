@extends('backend.layout')
@section('title','Quiz')
@section('content')

<style>
    .left-border{
        border-left: 3px solid;
    }
    .info_card h3{
        font-size: 1.2rem;
    }
    #optioncontainer{
        padding: 10px;
        border: 2px dashed #cccccc70;
    }
    .form-switch .form-check-input {
        width: 3.5em!important;        
        height: 1.5em!important;
        background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e);
        background-position: left center;
        border-radius: 2em;
    }
    .options{
        background: #00808014;
        padding: 10px;
        border-radius: 5px;
        margin-top: 25px;
    }
    .options .header{    
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .options>div p{
        margin-left:50px;
    }
    .optionBody>div p img{
        width:75px!important;
    }
    .optionBody{
        margin-top: 25px;
    }
    .optionBody>div{
        display: flex;
    }
    .questionBody{
        margin-top: 25px;
        margin-bottom: 25px;
    }
    .button-box{
        display: flex;
        justify-content: space-between;
    }
    .first-view{
        display: flex;
        justify-content: center;
        align-items: center;
        height: 10vh;
        margin-top: 15%;
        background: #0080802e;
        font-size: 20px;
        border: 2px dashed #00808052;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Add Quiz Quiestions</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/quizes">Quiz</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Quiz Questions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/quizes')}}" class="btn btn-danger text-white">Quiz List</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">

        <div class="row">
            <!-- column -->
            <div class="col-10">
                <div class="card">
                    <div class="card-body">
        
                    </div>
                    <div class="px-4 pb-4">
                        <h3 class="mb-3">Add Questions To ({{$quiz->title}})</h3>
                        <a href="javascript:void(0)" id="addQuestionsVisibility" class="btn btn-success text-white">Add Questions</a>
                        <a href="javascript:void(0)" id="viewQuestionsVisibility" class="btn btn-info text-white">View Questions </a>
                        <a href="{{ route('quizes.edit', ['quize' => $quiz->id]) }}" class="btn btn-danger text-white">Back</a>
                        <div class="card-body">
                            <div class="row" id="add_questions">
                                <div class="col-sm-3" style="    padding: 25px;background: #00808012;">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="medium">Select Standard</label>
                                            <select name="standards" id="standards" class="form-control js-example-basic-multiple">
                                                @foreach ($standards as $item)
                                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                @endforeach 
                                            </select>                        
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="medium">Select Subject</label>
                                            <select name="subject" id="subject" class="form-control js-example-basic-multiple">
                                                <option disabled selected>Select Subject</option>
                                            </select>                              
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="medium">Select Chapter</label>
                                            <select name="chapter" id="chapter" class="form-control js-example-basic-multiple">
                                                <option disabled selected>Select Chapter</option>
                                            </select>                               
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="medium">Select Topic <small class="text-muted">(optional)</small></label>
                                            <select name="topic" id="topic" class="form-control js-example-basic-multiple">
                                                <option disabled selected>Select Topic</option>
                                            </select>                           
                                        </div>
                                    </div>
                                    <button style="float:right;" id="filter_questions" class="btn btn btn-success text-white">Filter</button>
                                </div>
                                <div class="col-sm-9">
                                    <div class="container-fluid">
                                        <div class="card-title">
                                            <div class="question_no_title">

                                            </div>
                                            <div class="question-box">
                                                <div class="first-view">
                                                    Please Select Filter
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="view_questions" style="display:none;">                                
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-9">
                                    <div class="container-fluid">
                                        <div class="card-title">
                                            <div class="view_question_no_title">

                                            </div>
                                            <div class="view_question-box">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>        
    </div>    
</div>
<script>
    $(document).ready(function() {

        $('.js-example-basic-multiple').select2();
        $('#addQuestionsVisibility').on('click', function(){
            $("#add_questions").show();    
            $("#view_questions").hide();    
        });
        $('#viewQuestionsVisibility').on('click', function(){
            $("#add_questions").hide();    
            $("#view_questions").show();    
            var quiz_id ={{$quiz->id}};
            var url = '/admin/view-question'; 
            var csrfToken = "{{csrf_token()}}";
            var dataToSend = {
                quiz_id: quiz_id,
            };
            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                data: dataToSend,
                success: function(data) {
                    // Handle the success response here
                    console.log('Success:', data.questions);
                    console.log('Success:', data.questions.length);
                    $(".view_question_no_title").html("Questions (" + data.questions.length + ")");
                    // Check if the 'questions' property exists in the response data
                    if (data.hasOwnProperty('questions')) {
                        var questions = data.questions;
                        var questionBox = document.querySelector('.view_question-box');
                        questionBox.innerHTML = "";
                        questionBox.classList.add('col-sm-12');
                        questions.forEach(function(question, index) { 
                             

                            // Create the main container div
                            var optionsDiv = document.createElement('div');
                            optionsDiv.classList.add('options');

                            // Create the header div
                            var headerDiv = document.createElement('div');
                            headerDiv.classList.add('header');

                            // Create and set the index div
                            var indexDiv = document.createElement('div');
                            indexDiv.classList.add('index');
                            indexDiv.textContent = "Q. (" + (index + 1) + ")";

                            // Create and set the type div
                            var typeDiv = document.createElement('div');
                            typeDiv.classList.add('type');
                            typeDiv.textContent = question.question_type;

                            var correctMarks = document.createElement('div');
                            correctMarks.classList.add('correctMarks');
                            correctMarks.textContent = "Correct Marks. (" + (question.correct_marks ) + ")";


                            var correctOption = document.createElement('div');
                            correctOption.classList.add('correctOption');
                            correctOption.textContent = "Correct Marks. (" + (question.correct_answer ) + ")";

                            // Append index and type divs to the header div
                            headerDiv.appendChild(indexDiv);
                            headerDiv.appendChild(correctMarks);
                            headerDiv.appendChild(correctOption);
                            headerDiv.appendChild(typeDiv);

                            // Create the question body div
                            var questionBodyDiv = document.createElement('div');
                            questionBodyDiv.classList.add('questionBody');
                            questionBodyDiv.innerHTML = question.questions;

                            // Create a button for viewing options
                            // Create a div for the buttons
                            var buttonDiv = document.createElement('div');
                                buttonDiv.classList.add('button-box');

                                // Create a button for viewing options
                                var viewOptionsBtn = document.createElement('a');
                                viewOptionsBtn.classList.add('btn', 'btn-success', 'text-white');
                                viewOptionsBtn.textContent = 'View Options';

                                viewOptionsBtn.addEventListener('click', function () {
                                    if (optionBodyDiv.style.display === 'none') {
                                        optionBodyDiv.style.display = 'block'; // Show the options when button is clicked
                                    } else {
                                        optionBodyDiv.style.display = 'none'; // Hide the options when button is clicked again
                                    }
                                });
                                buttonDiv.appendChild(viewOptionsBtn);
                                
                                var addBtn = document.createElement('button');
                                addBtn.classList.add('btn', 'btn-danger', 'text-white');
                                addBtn.textContent = 'Remove';

                                addBtn.addEventListener('click', function () {
                                    addBtn.setAttribute('disabled', 'disabled');
                                    // alert('Question ID: ' + question.id + "Quiz ID: " + quiz_id);

                                    var url = '/admin/quiz/remove-question'; // Remove the trailing slash
                                    var dataToSend = {
                                        question_id: question.id,
                                        quiz_id: quiz_id,
                                    };
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                                        },
                                        data: dataToSend,
                                        success: function(data) {
                                            addBtn.textContent = 'Removed Successfully';
                                        },
                                        error: function(xhr, status, error) {
                                            // Handle errors here
                                            console.error('Error:', error);
                                        }
                                    });

                                });

                                // Append both buttons to the button div
                                buttonDiv.appendChild(viewOptionsBtn);
                                buttonDiv.appendChild(addBtn);

                            // Create the option body div
                            var optionBodyDiv = document.createElement('div');
                            optionBodyDiv.classList.add('optionBody');
                            optionBodyDiv.style.display = 'none';

                            var optionKeys = question.options;
                            var jsonObject = $.parseJSON(optionKeys);

                            $.each(jsonObject, function(index, option) { 
                                var optionElement = "<p>(" + index + ")" + option + "</p>";
 
                                var optionDiv = $('<div>').append(optionElement);
 
                                $(optionBodyDiv).append(optionDiv);

                            });
                            
                            // $('body').append(optionBodyDiv);
                            // Append all elements to the main container div
                            optionsDiv.appendChild(headerDiv);
                            optionsDiv.appendChild(questionBodyDiv);
                            optionsDiv.appendChild(buttonDiv);
                            optionsDiv.appendChild(optionBodyDiv);

                            // Append the main container div to your questionBox element
                            questionBox.appendChild(optionsDiv);
                        });
                    }                    
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error('Error:', error);
                }
            });
        });

        $('#filter_questions').on('click', function(){
            var standard = $("#standards").val();
            var subject = $("#subject").val();
            var chapter = $("#chapter").val();
            var topic = $("#topic").val();
            var quiz_id ={{$quiz->id}};
            var url = '/admin/fetch-question'; // Remove the trailing slash
            // Create an object to send as data
            var dataToSend = {
                standard_id: standard,
                chapter_id: chapter,
                topic_id: topic,
                subject_id: subject,
                quiz_id: quiz_id,
            };
            var csrfToken = "{{csrf_token()}}";
            // Send the AJAX request
            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                data: dataToSend,
                success: function(data) {
                    // Handle the success response here
                    console.log('Success:', data.questions);
                    console.log('Success:', data.questions.length);
                    $(".question_no_title").html("Questions (" + data.questions.length + ")");
                    // Check if the 'questions' property exists in the response data
                    if (data.hasOwnProperty('questions')) {
                        var questions = data.questions;
                        var questionBox = document.querySelector('.question-box');
                        questionBox.innerHTML = "";
                        questionBox.classList.add('col-sm-12');
                        questions.forEach(function(question, index) { 
                             

                            // Create the main container div
                            var optionsDiv = document.createElement('div');
                            optionsDiv.classList.add('options');

                            // Create the header div
                            var headerDiv = document.createElement('div');
                            headerDiv.classList.add('header');

                            // Create and set the index div
                            var indexDiv = document.createElement('div');
                            indexDiv.classList.add('index');
                            indexDiv.textContent = "Q. (" + (index + 1) + ")";

                            // Create and set the type div
                            var typeDiv = document.createElement('div');
                            typeDiv.classList.add('type');
                            typeDiv.textContent = question.question_type;

                            var correctMarks = document.createElement('div');
                            correctMarks.classList.add('correctMarks');
                            correctMarks.textContent = "Correct Marks. (" + (question.correct_marks ) + ")";


                            var correctOption = document.createElement('div');
                            correctOption.classList.add('correctOption');
                            correctOption.textContent = "Correct Marks. (" + (question.correct_answer ) + ")";

                            // Append index and type divs to the header div
                            headerDiv.appendChild(indexDiv);
                            headerDiv.appendChild(correctMarks);
                            headerDiv.appendChild(correctOption);
                            headerDiv.appendChild(typeDiv);

                            // Create the question body div
                            var questionBodyDiv = document.createElement('div');
                            questionBodyDiv.classList.add('questionBody');
                            questionBodyDiv.innerHTML = question.questions;

                            // Create a button for viewing options
                            // Create a div for the buttons
                            var buttonDiv = document.createElement('div');
                                buttonDiv.classList.add('button-box');

                                // Create a button for viewing options
                                var viewOptionsBtn = document.createElement('a');
                                viewOptionsBtn.classList.add('btn', 'btn-success', 'text-white');
                                viewOptionsBtn.textContent = 'View Options';

                                viewOptionsBtn.addEventListener('click', function () {
                                    if (optionBodyDiv.style.display === 'none') {
                                        optionBodyDiv.style.display = 'block'; // Show the options when button is clicked
                                    } else {
                                        optionBodyDiv.style.display = 'none'; // Hide the options when button is clicked again
                                    }
                                });
                                buttonDiv.appendChild(viewOptionsBtn);
                                
                                var addBtn = document.createElement('button');
                                addBtn.classList.add('btn', 'btn-success', 'text-white');
                                addBtn.textContent = 'Add';

                                addBtn.addEventListener('click', function () {
                                    addBtn.setAttribute('disabled', 'disabled');
                                    // alert('Question ID: ' + question.id + "Quiz ID: " + quiz_id);

                                    var url = '/admin/quiz/add-question'; // Remove the trailing slash
                                    var dataToSend = {
                                        question_id: question.id,
                                        quiz_id: quiz_id,
                                    };
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                                        },
                                        data: dataToSend,
                                        success: function(data) {
                                            addBtn.textContent = 'Added Successfully';
                                        },
                                        error: function(xhr, status, error) {
                                            // Handle errors here
                                            console.error('Error:', error);
                                        }
                                    });

                                });

                                // Append both buttons to the button div
                                buttonDiv.appendChild(viewOptionsBtn);
                                buttonDiv.appendChild(addBtn);

                            // Create the option body div
                            var optionBodyDiv = document.createElement('div');
                            optionBodyDiv.classList.add('optionBody');
                            optionBodyDiv.style.display = 'none';

                            var optionKeys = question.options;
                            var jsonObject = $.parseJSON(optionKeys);

                            $.each(jsonObject, function(index, option) { 
                                var optionElement = "<p>(" + index + ")" + option + "</p>";
 
                                var optionDiv = $('<div>').append(optionElement);
 
                                $(optionBodyDiv).append(optionDiv);

                            });
                            
                            // $('body').append(optionBodyDiv);
                            // Append all elements to the main container div
                            optionsDiv.appendChild(headerDiv);
                            optionsDiv.appendChild(questionBodyDiv);
                            optionsDiv.appendChild(buttonDiv);
                            optionsDiv.appendChild(optionBodyDiv);

                            // Append the main container div to your questionBox element
                            questionBox.appendChild(optionsDiv);
                        });
                    }                    
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error('Error:', error);
                }
            });
        });

        $('#standards').change(function() {
            // Get the selected value from the select element
            var selectedStandardId = $(this).val();
            
            // Check if a standard is selected (not an empty option)
            if (selectedStandardId) {
                // Define the URL to send the AJAX request to, including the selected standard ID
                var url = '/admin/get-subjects/' + selectedStandardId;
                
                // Send the AJAX request
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        var subjectSelect = $('#subject');

                        // Clear existing options (if any)
                        subjectSelect.empty();

                        // Iterate through the data and append options to the select element
                        data.data.forEach(function(subject) {
                            // Create a new option element
                            var option = $('<option>');
                            
                            // Set the value and text of the option based on the received data
                            option.val(subject.id);
                            option.text(subject.name);
                            
                            // Append the option to the select element
                            subjectSelect.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#subject').change(function() {
            // Get the selected value from the select element
            var selectedSubjectsId = $(this).val();
            
            // Check if a Subjects is selected (not an empty option)
            if (selectedSubjectsId) {
                // Define the URL to send the AJAX request to, including the selected Subjects ID
                var url = '/admin/get-chapters/' + selectedSubjectsId;
                
                // Send the AJAX request
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        var chapter = $('#chapter');

                        // Clear existing options (if any)
                        chapter.empty();

                        // Iterate through the data and append options to the select element
                        data.data.forEach(function(subject) {
                            // Create a new option element
                            var option = $('<option>');
                            
                            // Set the value and text of the option based on the received data
                            option.val(subject.id);
                            option.text(subject.name);
                            
                            // Append the option to the select element
                            chapter.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#chapter').change(function() {
            // Get the selected value from the select element
            var selectedChapterId = $(this).val();
            
            // Check if a Chapter is selected (not an empty option)
            if (selectedChapterId) {
                // Define the URL to send the AJAX request to, including the selected Chapter ID
                var url = '/admin/get-topics/' + selectedChapterId;
                
                // Send the AJAX request
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        var topic = $('#topic');

                        // Clear existing options (if any)
                        topic.empty();
                        // Create the initial "Select Topic" option that is disabled and selected by default
                        var initialOption = $('<option>', {
                            value: '', // Set an empty value for the initial option
                            text: 'Select Topic',
                            disabled: true,
                            selected: true
                        });

                        // Append the initial option to the select element
                        topic.append(initialOption);
                        // Iterate through the data and append options to the select element
                        data.data.forEach(function(subject) {
                            // Create a new option element
                            var option = $('<option>');
                            
                            // Set the value and text of the option based on the received data
                            option.val(subject.id);
                            option.text(subject.heading);
                            
                            // Append the option to the select element
                            topic.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error('Error:', error);
                    }
                });
            }
        });
    });
</script>
@endsection
