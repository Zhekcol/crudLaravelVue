<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import WarningButton from '@/Components/WarningButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head,useForm } from '@inertiajs/vue3';
import { nextTick,ref } from 'vue';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

const nombreInput = ref(null);
const modal = ref(false);
const title = ref('');
const operation = ref(1);
const id = ref('');

const props = defineProps({
    estudiantes: {type:Object},
});
const form = useForm({
    documento:'', nombre:'', telefono:'', email:'', direccion:'', ciudad:'', semestre:''
});
const formPage = useForm({});
const onPageClick = (event)=>{
    formPage.get(route('estudiantes.index',{page:event}));
}
const openModal = (op,documento,nombre,telefono,email,direccion,ciudad,semestre,estudiante) =>{
    modal.value = true;
    nextTick( () => nombreInput.value.focus());
    operation.value = op;
    id.value = estudiante;
    if(op == 1){
        title.value = 'Crear estudiante';
    }else{
        title.value = 'Editar estudiante';
        form.documento = documento;
        form.nombre = nombre;
        form.telefono = telefono;
        form.email = email;
        form.direccion = direccion;
        form.ciudad = ciudad;
        form.semestre = semestre;
    }
}
const closeModal = () =>{
    modal.value = false;
    form.reset();
}
const save = () =>{
    if (operation.value == 1) {
        form.post(route('estudiantes.store'),{
            onSuccess: () => {ok('Estudiante creado')}
        });
    }else{
        form.put(route('estudiantes.update',id.value),{
            onSuccess: () => {ok('Estudiante actualizado')}
        });
    }
}
const ok = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'success'});
}
const deleteEstudiante = (id,nombre) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'¿Seguro que quieres eliminar '+nombre+' ?',
        icon:'question', showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check"> Sí, eliminar </i>',
        cancelButtonText:'<i class="fa-solid fa-ban">Cancelar</i>'
    }).then((result) => {
        if(result.isConfirmed) {
            form.delete(route('estudiantes.destroy',id),{
                onSuccess: () =>{ok('Estudiante eliminado')}
            });
        }
    });
}
</script>

<template>
    <Head title="Estudiantes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Estudiantes</h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <PrimaryButton @click="$event => openModal(1)">
                        <i class="fa-solid fa-plus-circle"></i> Agregar
                    </PrimaryButton>
                </div>
            </div>
            <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">ID</th>
                            <th class="px-2 py-2">DOCUMENTO</th>
                            <th class="px-2 py-2">ESTUDIANTE</th>
                            <th class="px-2 py-2">TELEFONO</th>
                            <th class="px-2 py-2">EMAIL</th>
                            <th class="px-2 py-2">DIRECCIÓN</th>
                            <th class="px-2 py-2">CIUDAD</th>
                            <th class="px-2 py-2">SEMESTRE</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="est, i in estudiantes" :key="est.id">
                            <td class="border border-gray-400 px-2 py-2">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.documento }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.nombre }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.telefono }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.email }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.direccion }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.ciudad }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ est.semestre }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton 
                                @click="$event => openModal(2,est.documento,est.nombre,est.telefono,est.email,est.direccion,est.ciudad,est.semestre,est.id)">
                                    <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="$event => deleteEstudiante(est.id,est.nombre)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <VueTailwindPagination 
                :cuurent="estudiantes.currentPage" :total="estudiantes.total" 
                :per-page="estudiantes.perPage"
                @page-changed="$event => onPageClick($event)"
                >
                </VueTailwindPagination>
            </div>
        </div>
        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font.medium text-gray-900">{{ title }}</h2>
            <div class="p-3">
                <InputLabel for="documento" value="Documento:"></InputLabel>
                <TextInput id="documento"
                v-model="form.documento" type="text" class="mt-1 block w-3/4"
                placeholder="Documento"></TextInput>
                <InputError :message="form.errors.documento" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="nombre" value="Nombre:"></InputLabel>
                <TextInput id="nombre" ref="nombreInput"
                v-model="form.nombre" type="text" class="mt-1 block w-3/4"
                placeholder="Nombre"></TextInput>
                <InputError :message="form.errors.nombre" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="telefono" value="Telefono:"></InputLabel>
                <TextInput id="telefono"
                v-model="form.telefono" type="text" class="mt-1 block w-3/4"
                placeholder="Telefono"></TextInput>
                <InputError :message="form.errors.telefono" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="email" value="Email:"></InputLabel>
                <TextInput id="email"
                v-model="form.email" type="text" class="mt-1 block w-3/4"
                placeholder="Email"></TextInput>
                <InputError :message="form.errors.email" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="direccion" value="Direccion:"></InputLabel>
                <TextInput id="direccion"
                v-model="form.direccion" type="text" class="mt-1 block w-3/4"
                placeholder="Direccion"></TextInput>
                <InputError :message="form.errors.direccion" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="ciudad" value="Ciudad:"></InputLabel>
                <TextInput id="ciudad"
                v-model="form.ciudad" type="text" class="mt-1 block w-3/4"
                placeholder="Ciudad"></TextInput>
                <InputError :message="form.errors.ciudad" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="semestre" value="Semestre:"></InputLabel>
                <TextInput id="semestre"
                v-model="form.semestre" type="text" class="mt-1 block w-3/4"
                placeholder="Semestre"></TextInput>
                <InputError :message="form.errors.semestre" class="mt-2"></InputError>
            </div>
            <div class="p-3 mt-6">
                <PrimaryButton :disabled="form.processing" @click="save">
                    <i class="fa-solid fa-save"></i> Guardar
                </PrimaryButton>
            </div>
            <div class="p-3 mt-6 flex justify-end">
                <SecondaryButton class="ml-3" :disabled="form.processing"
                @click="closeModal">
                    Cancelar
                </SecondaryButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
