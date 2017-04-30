<li class="list-group-item">
    {!! $notification->data['text'] !!}
    <span class="pull-right">
        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans()}}
    </span>
</li>