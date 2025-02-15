<?php
session_start();
require_once '../tools/clean.php';
require_once '../classes/facilitator.class.php';

$viewOrgs = new Facilitator();

$facilitator_details = $viewOrgs->facilitator_details($_SESSION['user']['user_id']);
$students = $viewOrgs->assignStudent();
$courses = $viewOrgs->viewCourse();

?>

<section class="container-fluid w-100 h-100">
    <div class="modal-container"></div>
    <div class="h-18 w-100 border-bottom">
    <div class="h-50 w-100 position-relative custom-border-bottom d-flex align-items-center justify-content-center">
            <h1 class="ccs-green">College of Computing Studies</h1>
            <div class="dropdown text-end position-absolute" style="right: 5px;">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-regular fa-circle-user fs-4 crimson"></i>
                    </a>
                    <ul class="dropdown-menu text-small">
<li><a class="dropdown-item" href="<?= isset($_SESSION['user']['is_facilitator']) || isset($_SESSION['user']['is_facilitator']) ? '../log_out.php' : '../admin/admin_logout.php'; ?>">Sign out</a></li>
                    </ul>
            </div>
        </div>
        <div class="h-50 w-100 custom-border-bottom container-fluid lh-1 py-3 px-5">
            <p class="text-secondary fs-5 m-0">Assign Student/Overview</p>
            <h2 class="m-0">Assign Student</h2>
        </div>
    </div>
    <div class="container-fluid h-80 w-100 py-5 px-5">
        <div class="h-100 w-100 shadow rounded-large overflow-scroll">
            <div class="w-100 d-flex justify-content-between p-2">
                <div class="w-18 d-flex align-items-center justify-content-around">
                    <label for="search">
                        <i class="fa-solid fa-magnifying-glass fs-4"></i>
                    </label>
                    <input class="p-2 w-75" type="text" name="bsearch" id="search" placeholder="Search...">
                </div>
            </div>
            <div class="w-100 d-flex justify-content-between p-2">
                <select name="course" id="course" class="p-2 border rounded-3">
                    <option value="choose-course">Course</option>
                    <option value="">All</option>
                    <?php foreach($courses as $course):?>
                    <option value="<?= $course['course_code'] ?>"><?= $course['course_code'] ?></option>
                    <?php endforeach;?>
                </select>
            </div>




            <table id="table-student" class="max-h-100 w-100 table-hover position-relative">
                <thead>
                    <tr class="bg-light-crimson">
                        <th class="fs-4 text-white p-2 text-start" style="width: 5%;">No.</th>
                        <th class="fs-4 text-white p-2" style="width: 20%;">Student</th>
                        <th class="fs-4 text-white p-2" style="width: 8%;">Status</th>
                        <th class="fs-4 text-white p-2" style="width: 7%;">Course</th>
                        
                        <th class="fs-4 text-white p-2" style="width: 10%;">Section</th>
                        <th class="fs-4 text-white p-2" style="width: 15%;">Role</th>
                        <th class="fs-4 text-white p-2" style="width: 10%;">Year</th>
                        <th class="fs-4 text-white p-2" style="width: 45%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-2 fw-bold fs-5">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php $counter = 1; ?>
                        <?php foreach ($students as $student): ?>
                            <tr class="border-bottom shadow-hover">
                                <td class="p-2 text-center"><?= $counter; ?></td>
                                <td class="p-2">
                                    <?= clean_input($student['last_name']) . ', ' . clean_input($student['first_name']) . ' ' . clean_input($student['middle_name']); ?>
                                </td>
                                <td class="p-2"><?= clean_input($student['status']); ?></td>
                                <td class="p-2"><?= clean_input($student['course_code']); ?></td>
                                <td class="p-2"><?= clean_input($student['course_section']); ?></td>
                                <td class="p-2">
                                    <?php 
                                    if($student['is_head'] != 0) {
                                        echo  "Head at " . $student['org_name']; 
                                    }
                                    else if($student['is_assistant_head'] != 0) {
                                        echo  "Assistant Head at " . $student['org_name']; 
                                    }
                                    else if($student['is_collector'] != 0) {
                                        echo  "Fee Collector at " . $student['org_name']; 
                                    } else {
                                        echo 'None';
                                    }
                                     ?>
                                </td>
                                <td class="p-2"><?= clean_input($student['course_year']); ?></td>
                                <td class="p-2 text-nowrap" style="height: 8vh;">
                                    <?php if($student['status'] == 'Enrolled'){ if (!empty($student['is_head']) || !empty($student['is_assistant_head']) || !empty($student['is_collector'])){ ?>
                                        <a class="text-success text-decoration-none pt-3">Already Assigned</a>
                                        <?php
                                        if(!empty($student['is_assistant_head']) || !empty($student['is_collector'])){
                                            if($facilitator_details['is_head'] != 0 || ($facilitator_details['is_head'] != 0 && $student['is_collector'])){ ?>
                                        <a href="" data-id="<?= clean_input($student['student_id']); ?>" class="btn btn-warning resign-head" style="height: 3.6vh">Resign</a>
                                        <?php } } ?>
                                    <?php } else {?>
                                    <?php ?>
                                        <a data-id="<?= $student['student_id'] ?>" class="btn btn-success assign-officer">Assign</a>
                                    <?php } ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
