<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MagnifyingGlass from "@/Components/Icons/MagnifyingGlass.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, useForm, Link, usePage, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

defineProps({
  students: {
    type: Object,
    required: true,
  },

  classes: {
    type: Object,
    required: true,
  },
  
});



const deleteForm = useForm({});


const studentDestroy = (studentId) => {
  if (confirm("Are You Sure?")) {
    deleteForm.delete(route("students.destroy", studentId), {
      preserveScroll: true,
    });
  }
};

let search = ref(usePage().props.searchvalue),

  class_id = ref(usePage().props.class_id ??  ""),

  pageNumber = ref(1);



let studentUrl = computed(() => {

  let url = new URL(route("students.index"));
  url.searchParams.append("page", pageNumber.value);

  if (search.value) {
    url.searchParams.append("search", search.value);
  }

  if (class_id.value) {
    url.searchParams.append("class_id", class_id.value);
  }

  return url;

});



const updatedPageNumber = (link) => {

  pageNumber.value = link.url.split("=")[1];

};

watch(
  () => search.value,
  (value) => {
    if (value) {
        pageNumber.value = 1;
    }
  }
);


watch(
  () => studentUrl.value,
  (updatedStudentUrl) => {
    router.visit(updatedStudentUrl, {
      preserveScroll: true,
      preserveState: true,
      replace: true,
    });
  }
);


</script>

<template>
  <div>
    <Head title="Student List" />

    <AuthenticatedLayout>
      <div class="bg-gray-100 py-10">
        <div class="mx-auto max-w-7xl">
          <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
              <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Students</h1>
                <p class="mt-2 text-sm text-gray-700">
                  A list of all the Students.
                </p>
              </div>

              <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <Link
                  :href="route('students.create')"
                  class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                >
                  Add Student
                </Link>
              </div>
            </div>

            <div class="flex flex-col justify-start sm:flex-row mt-6">
              <div class="relative text-sm text-gray-800 col-span-3">
                <div
                  class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500"
                >
                  <MagnifyingGlass />
                </div>

                <input
                  v-model="search"
                  type="text"
                  autocomplete="off"
                  placeholder="Search students data..."
                  id="search"
                  class="block rounded-lg border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>

              <select v-model="class_id" class="block rounded-lg border-0 py-2 ml-5 text-gray-900 ring-1 ring-insert ring-gray-200 placeholder:text-gray-400 sm:text-sm sm:leading-6" name="" id="">
                <option value="">Filter By Class</option>
                  <option v-for="itemclass in classes.data" :key="itemclass.id" :value="itemclass.id" >{{itemclass.name}}</option>
              </select>
            </div>

            <div class="mt-8 flex flex-col">
              <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                  class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
                >
                  <div
                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative"
                  >
                    <table class="min-w-full divide-y divide-gray-300">
                      <thead class="bg-gray-50">
                        <tr>
                          <th
                            scope="col"
                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                          >
                            ID
                          </th>
                          <th
                            scope="col"
                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                          >
                            Name
                          </th>
                          <th
                            scope="col"
                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                          >
                            Email
                          </th>
                          <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                          >
                            Class
                          </th>
                          <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                          >
                            Section
                          </th>
                          <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                          >
                            Created At
                          </th>
                          <th
                            scope="col"
                            class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                          />
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="student in students.data" :key="student.id">
                          <td
                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                          >
                            {{ student.id }}
                          </td>
                          <td
                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                          >
                            {{ student.name }}
                          </td>
                          <td
                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                          >
                            {{ student.email }}
                          </td>
                          <td
                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                          >
                            {{ student.class.name }}
                          </td>
                          <td
                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                          >
                            {{ student.section.name }}
                          </td>
                          <td
                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                          >
                            {{ student.created_at }}
                          </td>

                          <td
                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                          >
                            <Link
                              :href="route('students.edit', student.id)"
                              class="text-indigo-600 hover:text-indigo-900"
                            >
                              Edit
                            </Link>

                            <button
                              @click="studentDestroy(student.id)"
                              class="ml-2 text-indigo-600 hover:text-indigo-900"
                            >
                              Delete
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <Pagination :data="students" :updatedPageNumber="updatedPageNumber"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </div>
</template>


<style>
</style>