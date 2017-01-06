package yoshino.repositories;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import yoshino.models.User;

/**
 * Created by Volio on 2016/12/18.
 */
@Repository
public interface UserRepository extends JpaRepository<User, Long> {

    User findOneByUid(Long uid);
}
