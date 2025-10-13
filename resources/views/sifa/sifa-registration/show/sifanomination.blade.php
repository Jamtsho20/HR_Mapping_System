    <label for=""><strong>SIFA BENEFIT NOMINATION</strong></label>
    <small>(s)<i> (I hereby nominate the person(s) mentioned below to have the conferred rights to claim my SIFA benefits upon my demise, as per the percentage of shares prescribed)</i></small>
    <br><br>
    <div class="table-responsive criteria">
        <table id="sifa_nomination" class="table table-condensed table-striped table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center">#</th>
                    <th width="20%">Name</th>
                    <th width="20%">Relationship</th>
                    <th width="20%">CID</th>
                    <th width="20%">Percentage of Share</th>
                    <th width="20%">Attacments(CID/Birth Certificate)</th>
                </tr>
            </thead>
            <tbody id="sifa_nomination_table">
                @foreach($sifaRegistration->sifaNomination as $key => $nomination)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $nomination->nominee_name }}</td>
                    <td>{{ $nomination->relation_with_employee }}</td>
                    <td>{{ $nomination->cid_number }}</td>
                    <td>{{ $nomination->percentage_of_share }}%</td>
                    <td>
                        @if ($nomination->attachment)
                        <a href="{{ asset('images/sifa/' . basename($nomination->attachment)) }}" target="_blank">
                            @if (Str::endsWith(strtolower($nomination->attachment), ['.jpg', '.jpeg', '.png']))
                            <img src="{{ asset('images/sifa/' . basename($nomination->attachment)) }}" alt="Attachment" width="50" height="50" style="object-fit: cover; border: 1px solid #ccc;">
                            @else
                            View Document
                            @endif
                        </a>
                        @else
                        <span class="text-muted">No Attachment</span>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
