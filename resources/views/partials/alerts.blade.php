@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif


<div class="d-flex justify-content-end mt-3">
    <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
    <button type="submit" class="btn btn-primary">Save</button>
</div>