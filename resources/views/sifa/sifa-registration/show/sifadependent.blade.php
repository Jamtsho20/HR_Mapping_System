<label for=""><strong>LIST OF DEPENDENTS</strong></label>
<small>(s)<i>(I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and liable and take actions as per the provisions of SIFA By-laws)</small></i>
<br><br>
<div class="table-responsive criteria">
    <table id="sifa_dependents" class="table table-condensed table-striped table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th class="text-center">#</th>
                <th width="25%">Name</th>
                <th width="25%">Relationship</th>
                <th width="25%">CID</th>
                <th width="25%">Attachments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sifaRegistration->sifaDependent as $key => $dependent)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $dependent->dependent_name }}</td>
                <td>{{ $dependent->relation_with_employee }}</td>
                <td>{{ $dependent->cid_number }}</td>
                <td>
                    @if ($dependent->attachment)
                    <a href="{{ asset('images/sifa/' . basename($dependent->attachment)) }}" target="_blank">
                        @if (Str::endsWith(strtolower($dependent->attachment), ['.jpg', '.jpeg', '.png']))
                        <img src="{{ asset('images/sifa/' . basename($dependent->attachment)) }}" alt="Attachment" width="50" height="50" style="object-fit: cover; border: 1px solid #ccc;">
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