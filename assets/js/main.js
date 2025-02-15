        //filterTable search of document and agreements in table
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = this.value.toLowerCase();
            var rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(function(row) {
                var cells = row.querySelectorAll('td');
                var match = false;

                cells.forEach(function(cell) {
                    if (cell.textContent.toLowerCase().indexOf(input) > -1) {
                        match = true;
                    }
                });

                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        //handel toggle menus
        function toggleMenu() {
            var menu = document.querySelector('nav');
            menu.classList.toggle('active');
        }


        // Function to handle file input change (for browsing PDF)
    function previewFile() {
        const fileInput = document.getElementById('file');
        const previewSection = document.getElementById('preview-section');
        const previewImage = document.getElementById('preview-image');
        const previewPdf = document.getElementById('preview-pdf');

        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            const fileType = file.type;

            if (fileType === 'application/pdf') {
                // Show PDF preview
                previewPdf.src = URL.createObjectURL(file);
                previewPdf.style.display = 'block';
                previewSection.style.display='block';
                previewImage.style.display = 'none';
            } else {
                // Show image preview (if the user uploads an image instead of a PDF)
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    previewPdf.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }

            // Show the preview section
            window.onload=()=>{
                previewSection.style.display = 'block';
            }
        }
    }

    // Function to handle camera capture
    function openCamera() {
        let input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.capture = 'environment'; // Use the rear camera
        input.onchange = function (event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    // Store the image data in a hidden input
                    document.getElementById('captured_image').value = e.target.result;

                    // Show the captured image in the preview section
                    const previewSection = document.getElementById('preview-section');
                    const previewImage = document.getElementById('preview-image');
                    const previewPdf = document.getElementById('preview-pdf');

                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    previewPdf.style.display = 'none';
                    previewSection.style.display = 'block';

                    alert("Image captured successfully!"); // Debugging message
                };
                reader.readAsDataURL(file);
            } else {
                alert("No file selected!");
            }
        };
        input.click();
    }





    //close preview documents and images in pdf
    function  ClosePreviewBox(){
        const preview=document.querySelector('#preview-section')
        preview.style.display='none'
    }

    function  ClosePreview(){
        const preview=document.querySelector('.doc')
        preview.style.display='none'
    }

    //open read documents
    function OpenPrivew(){
        const preview=document.querySelector('.doc')
        preview.style.display='block'
    }
    
    //closing the alter message box

    function CloseMSG(){
        const alterMSG=document.querySelector('.msg-container')
        alterMSG.style.display='none'
    }