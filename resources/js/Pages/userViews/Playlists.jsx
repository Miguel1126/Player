import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import TextInput from "@/Components/TextInput";
import { useRef, useState, useEffect } from "react";
import { useForm } from "@inertiajs/react";
import { Head } from "@inertiajs/react";
import DangerButton from "@/Components/DangerButton";
import PrimaryButton from "@/Components/PrimaryButton";
import WarningButton from "@/Components/WarningButton";
import Swal from "sweetalert2";
import axios from "axios";

export default function Dashboard(props) {
    const [modal, setModal] = useState(false);
    const [title, setTitle] = useState("");
    const [operation, setOperation] = useState(1);
    const TitleInput = useRef();
    const {
        data,
        setData,
        delete: destroy,
        post,
        put,
        processing,
        reset,
        errors,
    } = useForm({
        id: "",
        title: "",
        song_id: "",
    });

    const openModal = (op, id, title) => {
        setModal(true);
        setOperation(op);

        if (op === 1) {
            setTitle("Añadir lista de reproduccón");
            setData({ title: "" });
        } else {
            setTitle("Modificar lista de reproduccón");
            setData({ id: id, title: title });
        }
    };

    const [previewImage, setPreviewImage] = useState("");

    useEffect(() => {
        console.log("Componente desmontado");
        setPreviewImage("");
        setData({ title: "" });
    }, []);

    const closeModal = () => {
        setModal(false);
    };

    const save = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append("title", data.title);
        formData.append("song_id", data.song_id);

        try {
            if (operation === 1) {
                await axios.post(route("playlists.store"), formData, {
                    timeout: 30000,
                });
                closeModal();
                Swal.fire(
                    "lista de reproduccón agregado correctamente",
                    "",
                    "success"
                );
            } else {
                const response = await axios.post(
                    route("updatePlaylist", { playlist: data.id }),
                    formData
                );

                // Actualizar el estado después de una respuesta exitosa
                setData((prevData) => ({
                    ...prevData,
                    id: response.data.id,
                }));

                closeModal();
                Swal.fire(
                    "lista de reproduccón modificado correctamente",
                    "",
                    "success"
                );
                console.log(data);
                console.log(response);
            }
        } catch (error) {
            console.error("Error al enviar el formulario:", error);
        }
    };

    const handleDelete = async (playlistId) => {
        try {
            const csrfToken = document.head.querySelector(
                'meta[name="csrf-token"]'
            ).content;

            const response = await fetch(`/playlists/${playlistId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            if (response.ok) {
                console.log("Operación exitosa");
            } else {
                const errorData = await response.json();
                console.error("Error en la solicitud:", errorData);
            }
        } catch (error) {
            console.error("Error en la red o del servidor:", error);
        }
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Playlists
                </h2>
            }
        >
            <Head title="Playlists" />
            <div className="bg-white grid v-screen place-items-center">
                <div className="mt-3 mb-3 flex justify-end">
                    <PrimaryButton onClick={() => openModal(1)}>
                        <i className="fa-solid fa-plus-circle"></i>
                        Agregar
                    </PrimaryButton>
                </div>
            </div>
    
            <div className="bg-white grid v-screen place-items-center py-6">
                <h2 className="h2">Playlists activas</h2>
                <table className="table-auto border border-gray-400">
                    <thead>
                        <tr className="bg-gray-100">
                            <th className="px-2 py-2">#</th>
                            <th className="px-2 py-2">Titulo</th>
                            <th className="px-2 py-2">Cantidad</th>
                            <th className="px-2 py-2">Titulos</th>
                            <th className="px-2 py-2">Creado</th>
                            <th className="px-2 py-2">Estado</th>
                            <th className="px-2 py-2">Artista</th>
                            <th className="px-2 py-2">Editar</th>
                            <th className="px-2 py-2">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        {props.playlistsActive.map((playlist, i) => (
                            <tr key={playlist.id}>
                                <td className="border border-gray-400 px-2 py-2">
                                    {i + 1}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.title}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.songs_number}
                                </td>
                                {playlist.songs.map((song) => (
                                    <td
                                        key={song.id}
                                        className="border border-gray-400 px-2 py-2"
                                    >
                                        {song.title}
                                    </td>
                                ))}
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.creation_date}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.state}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.user.name}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    <WarningButton
                                        onClick={() =>
                                            openModal(
                                                2,
                                                playlist.id,
                                                playlist.title
                                            )
                                        }
                                    >
                                        <i className="fa-solid fa-edit"></i>
                                    </WarningButton>
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    <DangerButton
                                        onClick={() =>
                                            handleDelete(playlist.id)
                                        }
                                    >
                                        <i className="fa-solid fa-trash"></i>
                                    </DangerButton>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        
            <div className="bg-white grid v-screen place-items-center py-6">
            <h2 className="h2">Playlists Eliminadas</h2>
                <table className="table-auto border border-gray-400">
                    <thead>
                        <tr className="bg-gray-100">
                            <th className="px-2 py-2">#</th>
                            <th className="px-2 py-2">Titulo</th>
                            <th className="px-2 py-2">Cantidad</th>
                            <th className="px-2 py-2">Titulos</th>
                            <th className="px-2 py-2">Creado</th>
                            <th className="px-2 py-2">Estado</th>
                            <th className="px-2 py-2">Eliminado</th>
                            <th className="px-2 py-2">Artista</th>
                            <th className="px-2 py-2">Editar</th>
                            <th className="px-2 py-2">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        {props.playlistsDeleted.map((playlist, i) => (
                            <tr key={playlist.id}>
                                <td className="border border-gray-400 px-2 py-2">
                                    {i + 1}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.title}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.songs_number}
                                </td>
                                {playlist.songs.map((song) => (
                                    <td
                                        key={song.id}
                                        className="border border-gray-400 px-2 py-2"
                                    >
                                        {song.title}
                                    </td>
                                ))}
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.creation_date}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.state}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.deleted_at}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {playlist.user.name}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    <WarningButton
                                        onClick={() =>
                                            openModal(
                                                2,
                                                playlist.id,
                                                playlist.title
                                            )
                                        }
                                    >
                                        <i className="fa-solid fa-edit"></i>
                                    </WarningButton>
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    <DangerButton
                                        onClick={() =>
                                            handleDelete(playlist.id)
                                        }
                                    >
                                        <i className="fa-solid fa-trash"></i>
                                    </DangerButton>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            <Modal show={modal} onClose={closeModal}>
                <h2 className="p-3 text-lg font-medium text-gray-900">
                    {title}
                </h2>
                <form
                    onSubmit={save}
                    encType="multipart/form-data"
                    className="p-6"
                >
                    <div className="mt-6">
                        <label htmlFor="title">Titulo</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value={data.title}
                            onChange={(e) =>
                                setData({ ...data, title: e.target.value })
                            }
                            required
                            className="mt-1 block w-3/4"
                        />
                    </div>
                    <div className="mt-6 flex justify-end">
                        <PrimaryButton className="m-2" onClick={save}>
                            Guardar
                        </PrimaryButton>
                        <SecondaryButton className="m-2" onClick={closeModal}>
                            Cancelar
                        </SecondaryButton>
                    </div>
                </form>
            </Modal>
        </AuthenticatedLayout>
    );
}
