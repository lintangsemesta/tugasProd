<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            
            <a href="" class="btn btn-danger mb-3"><i class="fa fa-print"></i> Print</a>
            <a href="<?= base_url('eksport/pdf');?>" class="btn btn-warning mb-3">Export PDF</a>
            <a href="" class="btn btn-success mb-3">Export Excel</a>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Id. User</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($userr as $r) : ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $r['id'];?></td>
                            <td><?= $r['name']; ?></td>
                            <td><?= $r['email']; ?></td>
                            <td>
                                <!-- <a href="" class="badge badge-success">Edit Profile</a> -->
                            </td>
                        </tr>
                        <?php $i++ ?>
                        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->