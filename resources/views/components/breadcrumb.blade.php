<div class="section-header">
    <h1>
        {{$title}}
    </h1>
    <div class="section-header-breadcrumb">
        @php
            $segments = request()->segments();
            $items = count($segments);
            $i = 0;
        @endphp
        @foreach ($segments as $segment)
            <div class="breadcrumb-item {{++$i === $items ? "active" : ""}}">
                <a href="#">
                    {{ucfirst($segment)}}
                </a>
            </div>
        @endforeach
    </div>
</div>
