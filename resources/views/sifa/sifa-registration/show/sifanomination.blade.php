    <label for=""><strong>RETIREMENT AND SIFA BENEFIT NOMINATION</strong></label>
    <small>(s)<i> (I hereby nominate the person(s) mentioned below, who is/are member(s) of my family, to have the conferred right to claim the retirement and SIFA benefit upon my demise, as per the percentage of shares prescribed)</i></small>
    <br><br>
    <div class="table-responsive criteria">
        <table id="sifa_nomination" class="table table-condensed table-striped table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center">#</th>
                    <th width="25%">Name</th>
                    <th width="25%">Relationship</th>
                    <th width="25%">CID</th>
                    <th width="25%">Percentage of Share</th>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>