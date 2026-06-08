<template>
    <div class="container py-4">
        <h1 class="h3 mb-4">Rechten beheren</h1>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Gebruiker</th>
                                <th v-for="role in roles" :key="role.id" class="text-center" style="min-width:110px">
                                    {{ role.name }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in localUsers" :key="user.id">
                                <td class="ps-4 fw-semibold">{{ user.name }}</td>
                                <td v-for="role in roles" :key="role.id" class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            :checked="user.role_ids.includes(role.id)"
                                            :disabled="saving[`${user.id}-${role.id}`]"
                                            @change="toggle(user, role, $event.target.checked)"
                                        >
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="feedback" class="alert mt-3" :class="feedback.ok ? 'alert-success' : 'alert-danger'" role="alert">
            {{ feedback.message }}
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    roles:       Array,
    users:       Array,
    toggleRoute: String,
});

const localUsers = ref(props.users.map(u => ({ ...u, role_ids: [...u.role_ids] })));
const saving      = ref({});
const feedback    = ref(null);

const toggle = async (user, role, isAssigned) => {
    const key = `${user.id}-${role.id}`;
    saving.value[key] = true;
    feedback.value = null;

    const userRef = localUsers.value.find(u => u.id === user.id);

    try {
        await axios.patch(route('admin.roles.user.toggle', [role.id, user.id]), {
            is_assigned: isAssigned,
        });

        if (isAssigned) {
            userRef.role_ids.push(role.id);
        } else {
            userRef.role_ids = userRef.role_ids.filter(id => id !== role.id);
        }

        feedback.value = { ok: true, message: `Rol "${role.name}" ${isAssigned ? 'toegewezen aan' : 'verwijderd van'} ${user.name}.` };
    } catch {
        feedback.value = { ok: false, message: 'Er is een fout opgetreden. Probeer opnieuw.' };
    } finally {
        saving.value[key] = false;
        setTimeout(() => { feedback.value = null; }, 3000);
    }
};
</script>
