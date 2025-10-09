<div>
    <h3>New contact form submission</h3>
    <p><strong>Name:</strong> {{ $data['name'] ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}</p>
    @if(!empty($data['subject']))
        <p><strong>Subject:</strong> {{ $data['subject'] }}</p>
    @endif
    <hr>
    <p>{{ nl2br(e($data['message'] ?? '')) }}</p>
</div>