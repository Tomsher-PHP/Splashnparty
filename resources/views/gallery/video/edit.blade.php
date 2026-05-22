@extends('layouts.app')

@section('content')

@include('gallery.video.partials.form', [
    'action' => route('video-gallery.update', $video_gallery->id),
    'method' => 'PUT',
    'title' => 'Edit Video Gallery',
    'buttonText' => 'Update Gallery',
    'gallery' => $video_gallery
])

@endsection

<script>

document.addEventListener('DOMContentLoaded', function () {

    let wrapper = document.getElementById('video-wrapper');

    if (wrapper) {

        new Sortable(wrapper, {

            animation: 200,

            ghostClass: 'sortable-ghost',

            chosenClass: 'sortable-chosen',

            onEnd: function () {

                updateTitles();

                saveOrder();

            }

        });

    }

    function updateTitles() {

        document.querySelectorAll('.sortable-item').forEach(function(item, index){

            item.querySelector('.video-title').innerHTML = `
                <i class="ri-youtube-fill text-danger"></i>
                Video ${index + 1}
            `;

        });

    }

    function saveOrder() {

        let links = [];

        document.querySelectorAll('input[name="youtube_link[]"]').forEach(function(input){

            links.push(input.value);

        });

        fetch("{{ route('video-gallery.sort') }}", {

            method: "POST",

            headers: {

                "Content-Type": "application/json",

                "X-CSRF-TOKEN": "{{ csrf_token() }}"

            },

            body: JSON.stringify({

                id: "{{ $video_gallery->id }}",

                youtube_link: links

            })

        });

    }

});

</script>