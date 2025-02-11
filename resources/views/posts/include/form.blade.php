<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="title">{{ __('Title') }}</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ isset($post) ? $post->title : old('title') }}" placeholder="{{ __('Title') }}" required />
            @error('title')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="body">{{ __('Body') }}</label>
            <textarea name="body" id="body" class="form-control @error('body') is-invalid @enderror" placeholder="{{ __('Body') }}" required>{{ isset($post) ? $post->body : old('body') }}</textarea>
            @error('body')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>