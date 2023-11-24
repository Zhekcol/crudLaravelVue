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
    asignaturas: {type:Object},
});
const form = useForm({
    nombre:'', descripcion:'', creditos:'', areaConocimiento:'', seleccion:''
});
const formPage = useForm({});
const onPageClick = (event)=>{
    formPage.get(route('asignaturas.index',{page:event}));
}
const openModal = (op,nombre,descripcion,creditos,areaConocimiento,seleccion,asignatura) =>{
    modal.value = true;
    nextTick( () => nombreInput.value.focus());
    operation.value = op;
    id.value = asignatura;
    if(op == 1){
        title.value = 'Crear nueva asignatura';
    }else{
        title.value = 'Editar asignatura';
        form.nombre = nombre;
        form.descripcion = descripcion;
        form.creditos = creditos;
        form.areaConocimiento = areaConocimiento;
        form.seleccion = seleccion;
    }
}
const closeModal = () =>{
    modal.value = false;
    form.reset();
}
const save = () =>{
    if (operation.value == 1) {
        form.post(route('asignaturas.store'),{
            onSuccess: () => {ok('Asignatura creada')}
        });
    }else{
        form.put(route('asignaturas.update',id.value),{
            onSuccess: () => {ok('Asignatura actualizada')}
        });
    }
}
const ok = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'Con éxito'});
}
const deleteAsignatura = (id,nombre) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'¿Seguro que quieres eliminar '+nombre+' ?',
        icon:'question', showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check">Sí, eliminar</i>',
        cancelButtonText:'<i class="fa-solid fa-ban">Cancelar</i>'
    }).then((result) => {
        if(result.isConfirmed) {
            form.delete(route('asignaturas.destroy',id),{
                onSuccess: () =>{ok('Asignatura eliminada')}
            });
        }
    });
}
</script>

<template>
    <Head title="Asignaturas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Asignaturas</h2>
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
                            <th class="px-2 py-2">NOMBRE</th>
                            <th class="px-2 py-2">DESCRIPCION</th>
                            <th class="px-2 py-2">CREDITOS</th>
                            <th class="px-2 py-2">AREA DE CONOCIMIENTO</th>
                            <th class="px-2 py-2">SELECCION</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asig, i in asignaturas" :key="asig.id">
                            <td class="border border-gray-400 px-2 py-2">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ asig.nombre }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ asig.descripcion }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ asig.creditos }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ asig.areaConocimiento }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ asig.seleccion }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton 
                                @click="$event => openModal(2,asig.nombre,asig.descripcion,asig.creditos,asig.areaConocimiento,asig.seleccion,asig.id)">
                                    <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="$event => deleteAsignatura(asig.id,asig.nombre)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <VueTailwindPagination 
                :cuurent="asignaturas.currentPage" :total="asignaturas.total" 
                :per-page="asignaturas.perPage"
                @page-changed="$event => onPageClick($event)"
                >
                </VueTailwindPagination>
            </div>
        </div>
        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font.medium text-gray-900">{{ title }}</h2>
            <div class="p-3">
                <InputLabel for="nombre" value="Nombre:"></InputLabel>
                <TextInput id="nombre" ref="nombreInput"
                v-model="form.nombre" type="text" class="mt-1 block w-3/4"
                placeholder="Nombre"></TextInput>
                <InputError :message="form.errors.nombre" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="descripcion" value="Descripción:"></InputLabel>
                <TextInput id="descripcion" 
                v-model="form.descripcion" type="text" class="mt-1 block w-3/4"
                placeholder="Descripción"></TextInput>
                <InputError :message="form.errors.descripcion" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="creditos" value="Creditos:"></InputLabel>
                <TextInput id="creditos"
                v-model="form.creditos" type="text" class="mt-1 block w-3/4"
                placeholder="Creditos"></TextInput>
                <InputError :message="form.errors.creditos" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="areaConocimiento" value="Area de conocimiento:"></InputLabel>
                <TextInput id="areaConocimiento"
                v-model="form.areaConocimiento" type="text" class="mt-1 block w-3/4"
                placeholder="Area de conocimiento"></TextInput>
                <InputError :message="form.errors.areaConocimiento" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="seleccion" value="Selección:"></InputLabel>
                <TextInput id="seleccion"
                v-model="form.seleccion" type="text" class="mt-1 block w-3/4"
                placeholder="Selección"></TextInput>
                <InputError :message="form.errors.seleccion" class="mt-2"></InputError>
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
