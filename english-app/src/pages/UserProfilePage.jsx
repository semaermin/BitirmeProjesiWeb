import Navbar from '../components/Navbar';
import UserProfile from '../components/UserProfile';

function UserProfilePage() {
  return (
    <div>
      <Navbar item="profile"></Navbar>
      <UserProfile></UserProfile>
    </div>
  );
}

export default UserProfilePage;
