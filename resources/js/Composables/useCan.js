import { usePage } from '@inertiajs/vue3'

export function useCan() {
    const page = usePage()

    const can = (permission) => {
        return page.props.auth.permissions.includes(permission)
    }

    return { can }
}
