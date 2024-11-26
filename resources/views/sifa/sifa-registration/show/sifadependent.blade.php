<label for=""><strong>SIFA Dependents</strong></label>
<small>(s)<i>(I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and responsible for any legal and financial damages arising thereafter)</small></i>
<br><br>
    <div class="table-responsive criteria">
        <table id="sifa_dependents" class="table table-condensed table-striped table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center">#</th>
                    <th width="33%">Name</th>
                    <th width="33%">Relationship</th>
                    <th width="33%">CID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sifaRegistration->sifaDependent as $key => $dependent)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $dependent->dependent_name }}</td>
                    <td>{{ $dependent->relation_with_employee }}</td>
                    <td>{{ $dependent->cid_number }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>