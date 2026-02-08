<html>
    <head>

    </head>

    <body>
        <div class="wrapper">
            @foreach($memberships as $membership)
                <div class="membership">
                    Type: {{ \App\Models\Membership::TYPES[$membership->type] }}
                    @if($membership->members->count())
                        <h3>Members</h3>
                        <ul>
                            @foreach($membership->members as $member)
                                <li>{{ $member->name }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($membership->contacts->count())
                        <h3>Contacts</h3>
                        <ul>
                            @foreach($membership->contacts as $contact)
                                <li>{{ $contact->name }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <a href="{{ route('memberships.edit', $membership) }}">View</a>
                </div>
            @endforeach
        </div>
    </body>

    <footer>
        <style>
            * {
                margin: 0;
                padding: 0;
            }

            .membership {
                border: 1px solid black;
                flex-basis: 25%;
                flex-grow: 0;
                flex-shrink: 0;
                padding: 25px;
            }

            .wrapper {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin: 10px auto;
                width: 1200px;
            }

            a {
                display: block;
                margin-top: 15px;
            }

            h3 {
                margin-top: 10px;
            }

            ul {
                margin-top: 5px;
                padding-left: 20px;
            }
        </style>
    </footer>
</html>