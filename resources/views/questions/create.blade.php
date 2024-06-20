@extends('backend.layout')
@section('title','Dashbord')
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
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Create Question</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/questions">Questions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Questions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/questions')}}" class="btn btn-danger text-white">Question List</a>
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
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
        
                    </div>
                    <div class="px-4 pb-4">
                        <h3 class="mb-3">Question Form</h3>
                        <form action="{{url('admin/questions')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="medium">Select Question Types</label>
                                <select name="question_type" id="question_type" class="form-control">
                                    @foreach ($questionTypeArray as $item)
                                    <option value="{{$item['code']}}">{{$item['name']}}</option>
                                    @endforeach 
                                </select>
                                @error('question_type')
                                    <span class="text-danger">Please select Question Type</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="medium">Select Standard</label>
                                        <select name="standards" id="standards" class="form-control">
                                            @foreach ($standards as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach 
                                        </select>
                                        @error('standards')
                                            <span class="text-danger">Please select Standard</span>
                                        @enderror                                
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="medium">Select Subject</label>
                                        <select name="subject" id="subject" class="form-control">
                                            <option disabled selected>Select Subject</option>
                                        </select>
                                        @error('subject')
                                            <span class="text-danger">Please select Subject Type</span>
                                        @enderror                                
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="medium">Select Chapter</label>
                                        <select name="chapter" id="chapter" class="form-control">
                                            <option disabled selected>Select Chapter</option>
                                        </select>
                                        @error('Chapter')
                                            <span class="text-danger">Please select Question Type</span>
                                        @enderror                                
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="medium">Select Topic <small class="text-muted">(optional)</small></label>
                                        <select name="topic" id="topic" class="form-control">
                                            <option disabled selected>Select Topic</option>
                                        </select>
                                        @error('topic')
                                            <span class="text-danger">Please select Question Type</span>
                                        @enderror                                
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="question">Question</label>
                                <textarea name="question" id="question"></textarea>
                                @error('question')
                                    <span class="text-danger">Please add question</span>
                                @enderror   
                            </div>
                            <div id="optioncontainer" class="row">
                                
                            </div>
                            <div class="form-group justify-content-center d-flex">
                                <button class="btn btn btn-primary my-2 mb-2 align-center text-white" id="addButton">Add Option</button>
                            </div>
                            <div class="form-group" id="correct_answere">
                                <label for="correct_ans">Correct Answere</label>
                                <select id="optionDropdown" name="correct_ans" class="form-control"></select>
                            </div>
                            <div class="form-group" id="correct_answere">
                                <label for="correct_ans">Correct Marks</label>
                                <input type="number" name="correct_marks" class="form-control">
                                @error('correct_marks')
                                    <span class="text-danger">Please enter Correct Marks</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="explanation">Explanation <small class="text-muted">(optional)</small></label>
                                <textarea name="explanation" id="explanation"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn btn-success text-white">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </div>    
</div>

<script>
    $(document).ready(function() {
        $('#question').summernote({
            height:200,                 
            minHeight: 200,             
            maxHeight: 200,       
            disableResizeEditor: true
        });
        $('#explanation').summernote({
            height:100,                 
            minHeight: 100,             
            maxHeight: 100,       
            disableResizeEditor: true
        });
        $('.dropdown-item').on('click', function() {
            // Close the dropdown when an item is clicked
            $('.note-dropdown-menu ').hide();
        });
        $('.dropdown-toggle').on('click', function() {
            // Toggle the dropdown menu visibility
            var dropdownMenu = $(this).next('.dropdown-menu');
            if (dropdownMenu.is(':visible')) {
                dropdownMenu.hide();
            } else {
                dropdownMenu.show();
            }
        });
        $('.dropdown-toggle').dropdown();
        const selectedQuestionType = '';


        const optioncontainer = document.getElementById('optioncontainer');
        const addButton = document.getElementById('addButton');
        const optionDropdown = document.getElementById('optionDropdown'); // Reference to the select dropdown

        let optionCount = 0;
        let usedLabels = new Set(); // To track labels that have been used
        let nextLabel = '';
        // Function to add a new option
        function addOption(status) {

            $('#addButton').show();
            // Increment the optionCount
            optionCount++;
            console.log(optionCount);
            // Find the next available label (A, B, C, ...)
            
            for (let i = 0; i < 26; i++) {
                const label = String.fromCharCode(65 + i);
                if (!usedLabels.has(label)) {
                    nextLabel = label;
                    usedLabels.add(label);
                    break;
                }
            }

            // Create a new div to hold the option and remove button
            const optionDiv = document.createElement('div');
            const optionInnerDiv = document.createElement('div');
            optionDiv.className = 'option-container col-sm-6';
            optionInnerDiv.className = 'option-inner-container';

            // Create a label element with the current option label (A, B, C, ...)
            const label = document.createElement('label');
            label.textContent = 'Option ' + nextLabel;

            // Create a new input element
            const newTextbox = document.createElement('textarea');
            newTextbox.type = 'text'; // Set the input type
            newTextbox.name = 'options[' + nextLabel + ']';
            newTextbox.setAttribute('required', true);
            newTextbox.className = 'form-control summernote-textarea';

            // Create a remove button
            const removeButton = document.createElement('button');
            removeButton.innerHTML = '<i class="mdi mdi-window-close"></i>';
            removeButton.className = 'remove_button btn btn-danger text-white ml-2 my-2 mb-4';
            removeButton.id = 'remove_button';
            if(status){
                removeButton.disabled = true;     
                $('#addButton').hide();
            }
            // Add event listener to the remove button
            removeButton.addEventListener('click', function () {
                // Remove the optionDiv when the remove button is clicked
                optioncontainer.removeChild(optionDiv);
                usedLabels.delete(nextLabel); // Free up the label for reuse
                // Remove the corresponding option from the dropdown
                const optionToRemove = optionDropdown.querySelector(`option[value="${nextLabel}"]`);
                if (optionToRemove) {
                    optionDropdown.removeChild(optionToRemove);
                }
            });

            // Append the label, textbox, and remove button to the optionDiv
            optionDiv.appendChild(label);
            optionDiv.appendChild(optionInnerDiv);
            optionInnerDiv.appendChild(newTextbox);
            optionInnerDiv.appendChild(removeButton);

            // Append the optionDiv to the container
            optioncontainer.appendChild(optionDiv);

            optioncontainer.appendChild(optionDiv);

            // Add this option as an option in the select dropdown
            const optionInDropdown = document.createElement('option');
            optionInDropdown.value = nextLabel;
            optionInDropdown.text = 'Option ' + nextLabel;
            optionDropdown.appendChild(optionInDropdown);
        }

        // Add four options on page load
        $('#question_type').change(function() {
            var selectedQuestionType = $(this).val();    
            if(selectedQuestionType == 'MSA'){
                optioncontainer.innerHTML = '';
                optionCount = 0;
                $('#correct_answere').show();                
                usedLabels = new Set(); 
                optionDropdown.innerHTML = '';
                for (let i = 0; i < 4; i++) {
                    addOption();                    
                }   
                $('.summernote-textarea').each(function() {
                    $(this).summernote({
                        height: 100,
                        minHeight: 100,
                        maxHeight: 100,
                        disableResizeEditor: true,
                        toolbar: [
                            // Other toolbar buttons
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['insert', ['link', 'picture']],
                            // Other toolbar buttons
                        ],
                        buttons: {
                            superscript: addSuperscriptButton
                        }
                    });
                });
            }else if(selectedQuestionType == 'TOF'){
                optioncontainer.innerHTML = '';
                optionCount = 0;
                $('#correct_answere').show();                
                usedLabels = new Set(); 
                optionDropdown.innerHTML = '';
                for (let i = 0; i < 2; i++) {
                    addOption();
                }  
            }else if(selectedQuestionType == 'FIB'){
                optioncontainer.innerHTML = '';
                optionCount = 0;
                $('#correct_answere').hide();       
                usedLabels = new Set(); 
                optionDropdown.innerHTML = '';
                for (let i = 0; i < 1; i++) {
                    addOption(true);
                }  
                const newoptioncontainer = document.getElementById('optioncontainer');
                var labelElement = newoptioncontainer.querySelector('label');
                console.log(labelElement,'asdfsdfsdfsafsa');
                labelElement.textContent = 'Expected Correct Answere';
            }else if(selectedQuestionType == 'SAQ'){
                optioncontainer.innerHTML = '';
                optionCount = 0;
                $('#correct_answere').hide();              
                usedLabels = new Set(); 
                optionDropdown.innerHTML = '';
                for (let i = 0; i < 1; i++) {
                    addOption(true);
                }  
                const newoptioncontainer = document.getElementById('optioncontainer');
                var labelElement = newoptioncontainer.querySelector('label');
                console.log(labelElement,'asdfsdfsdfsafsa');
                labelElement.textContent = 'Expected Correct Answere';
            }
            
        });
        

        // Add event listener to the button to add more options
        addButton.addEventListener('click', function (e) {
            e.preventDefault();
            addOption();
            $('.summernote-textarea').summernote({
                height: 100,
                minHeight: 100,
                maxHeight: 100,
                disableResizeEditor: true,
                toolbar: [
                    // Other toolbar buttons
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['insert', ['link', 'picture']],
                    
                    // Other toolbar buttons
                ],
                buttons: {
                    superscript: addSuperscriptButton
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

        
        function addSuperscriptButton(context) {
            var ui = $.summernote.ui;

            // Create a button
            var button = ui.button({
                contents: '<i class="fas fa-superscript"></i>',
                tooltip: 'Superscript',
                click: function () {
                    // Toggle superscript on/off
                    context.invoke('editor.toggleSuperscript');
                }
            });

            return button.render();
        }
    });
</script>
@endsection
